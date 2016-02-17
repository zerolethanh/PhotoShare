<?php

namespace App\Http\Controllers;

use App\Event;
use App\Events\PhotoAdded;
use App\Events\PhotoDeleted;
use App\Photo;
use App\User;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PhotoController extends Controller
{
    protected $user;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('photo');
        $this->user = Auth::user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //please dont delete me
    }

    public function postDelete(Request $request, $photo_id)
    {

        $deleted = false;
        $photo = Photo::findOrFail($photo_id);
        if ($photo->user_id != $request->user()->id) {
            $msg = '写真所有者ではないため削除出来ません。';
            return compact('deleted', 'msg');
        }
        $deleted = $photo->delete();
        $photo->load('event');
        event(new PhotoDeleted($photo->event));
        if ($request->has('mobile')) {
            $photos = (new EventController())->anyPhotos($request, $photo->event_id);
            if (isset($photos["photos"]["all"])) {
                $photos = $photos["photos"]["all"];
            }
            return compact('deleted', 'photos');
        } else {
            return compact('deleted', 'photo', 'event');
        }
    }

//    GET /photos/upload/$event_id
    public function getUpload(Request $request, $event_id = null)
    {
        $event_id = $event_id ?: $request->event_id;
        if (!isset($event_id)) {
            return 'not valid <br>use $request->event_id, or photos/upload/$event_id';
        }

        $event = $this->user->events()->findOrFail($event_id);

        $this->user->increment('photo_last_group_id');

        if (file_exists($view = "/var/www/html/js/jqFileUploads/index.php")) {
            return view()->file($view, ['gid' => $this->user->photo_last_group_id, 'event' => $event]);
        }

        return 'view not found';
    }

    /*
     * GET POST /photos/download/$photo_id
     */
    public function anyDownload(Request $request, $photo_id)
    {
        $photo = Photo::findOrFail($photo_id);
        return response()->download($this->photoPath($photo), ($photo->ori_name ?: $photo->name) . "." . $photo->extension);
    }

    /*
     * GET POST /photos/download-all/$event_id
     * @return download photos zip
     */
    public function anyDownloadAll(Request $request, $event_id)
    {
        $event = $this->user->events()->findOrFail($event_id);
        $photos = $event->photos;
//        $count= count($photos);
//        $q = \utils::queryInfos();
//return compact('count','q','photos');

        if (!count($photos)) return ['no photos'];

        $photosPaths = [];
        $photosNames = [];
        foreach ($photos as $p) {
            $photosPaths[] = $this->photoPath($p);
            $photosNames[] = "{$p->id}_{$p->ori_name}.{$p->extension}";
        }
//        dd($photosNames);

        return Event::downloadZipPhotos($photosPaths, $photosNames, $event->event_name);
    }

//POST　 /photo　
    public function store(Request $request)
    {
        /*
         * _token:
         * gid:
         * event_id:
         *
         */
        $this->validate($request, ['userfile' => 'required']);

        if ($request->hasFile('userfile') && ($uploadedFiles = $request->file('userfile'))) {

            $group_id = $request->gid;//$this->getNewInsertGroupId($request);
            $event_id = $request->event_id;
            $user_id = $this->user->id;

            $event = Event::findOrFail($event_id);

            DB::beginTransaction();

            foreach ($uploadedFiles as $file) {
                if ($file instanceof UploadedFile && $file->isValid()) {

                    $ori_name = explode('.', $file->getClientOriginalName());
                    if (count($ori_name) > 1) {
                        array_pop($ori_name);
                        $ori_name = implode('.', $ori_name);
                    } else {
                        $ori_name = date('Y_m_d_H_i_s');
                    }
                    $photo = $event->photos()->save(
                        new Photo(
                            ['extension' => $file->guessExtension(),
                                'name' => md5($file->getClientOriginalName()),
                                'ori_name' => $ori_name,
                                'mime' => $file->getMimeType(),
                                'size' => $file->getSize(),
                                'group_id' => $group_id,
                                'user_id' => $user_id

                            ]));
                    if ($photo) {
                        if ($this->moveFile($file, $photo)) {
                            $this->createImage($this->photoPath($photo), $photo);
                            $this->createThumb($this->photoPath($photo), $photo);

                            $photos[] = $photo;
                        };
                    }
                }
            }

            DB::commit();


            event((new PhotoAdded($event, $this->user)));

            return ['files' => isset($photos) ? $photos : []];

        };
        $request_all = $request->all();
        return compact('request_all');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        return view('photo.upload',
            $this->getUserPhotoGroupInfo()
        );

    }

    static function getUserPhotoGroupInfo()
    {
        return [
            'group_ids' => Auth::user()->photos()->lists('group_id')->unique()->values(),
            'photo_last_group_id' => Auth::user()->photo_last_group_id
        ];
    }

    public function getReal(Request $request)
    {
        return view('photo.real.mainpage2');
    }

    public function getGroup(Request $request, $gid)
    {

        $links = $this->user->photoGroup($request->gid)->lateOrder()->lists('id')->map(function ($val) {
            return "/photo/$val";
        });
//        return view('photo.show', compact('links'));

        return view('photo.real.mainpage2', compact('links'));
    }


    public function getCreate(Request $request)
    {
        DB::beginTransaction();

        $this->user->increment('photo_last_group_id');

        $newEvent = $this->user->events()->create([
            'event_name' => '[welcome]',
            'all_day' => 1,
            'or_time' => Carbon::now(),
            'uuid' => Uuid::uuid()
        ]);

        $newEvent->fill(['shared_id' => Crypt::encrypt($newEvent->id)])->save();

        DB::commit();

        return redirect('/event');

//        return view()->file('/var/www/html/js/jqFileUploads/index.php',
//            ['gid' => $this->user->photo_last_group_id,
//                'event_id' => $newEvent->id,
//            ]
//        );
    }


    public function getEventFromRequest()
    {

    }

    public function getNewInsertGroupId(Request $request)
    {
        if ($request->is_new_group) {
            $this->user->increment('photo_last_group_id');
            return $this->user->photo_last_group_id;
        }
        if (($gid = ($request->gid)) && $this->isValidGroupId($gid))
            return $gid;
        else
            return $this->user->photo_last_group_id;
        //
    }

    public function isValidGroupId($id)
    {
        return is_numeric($id) && ($id = intval($id)) && $id > 0;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        //
        $photo = $this->user->events()->photos()->findOrFail($id);
//        dd($photo);
        if ($photo) {
            if ($request->thumb) {
                if ($this->isThumbExists($photo)) {
                    $this->showImageDirectToBrowser($this->photoThumbFullPath($photo));
                }
            }
            $this->showImageDirectToBrowser($this->photoPath($photo));
        }
        return 'no photo exists';
//        $photo = $this->user->photo($id);
//        if ($photo) {
//            if ($request->thumb) {
//                if ($this->isThumbExists($photo)) {
//                    $this->showImageDirectToBrowser($this->photoThumbFullPath($photo));
//                }
//            }
//            $this->showImageDirectToBrowser($this->photoPath($photo));
//        }
//        return 'no photo exists';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

//    public function getCreatePhotos(Request $request)
//    {
//        $created = 0;
//        DB::beginTransaction();
//        foreach (User::all() as $u) {
//            for ($i = 0; $i < $request->num; $i++) {
//                $u->photos()->create(['group_id' => mt_rand(1, 20)]);
//                ++$created;
//            }
//        }
//        DB::commit();
//        return $created;
//    }

    public static function createFileOrDirIfNotExists($fileOrDir)
    {
        if (file_exists($fileOrDir)) return true;
        else {
            return mkdir($fileOrDir, 0777, true);
        }
    }

    public static function showImageDirectToBrowser($filePath)
    {
        $size = getimagesize($filePath);
        $fp = fopen($filePath, "rb");

        if ($size && $fp) {
            header("Content-type: {$size['mime']}");
            fpassthru($fp);
            exit;
        } else {
        }
    }

    function moveFile(UploadedFile $file, $photo)
    {
        return $file->move($this->photoDir($photo), $this->photoName($photo));
    }

    function createImage($filename, Photo $photo)
    {
        header("Content-Type: $photo->mime");
//        list($width, $height) = getimagesize($filename);
        $func = "imagecreatefrom{$photo->extension}";
        if (function_exists($func)) {
            $source = call_user_func($func, $filename);
        } else {
            $source = imagecreatefromjpeg($filename);
        }
        $this->image_fix_orientation($source, $filename);
        $func = "image{$photo->extension}";
        if (function_exists($func)) {
            call_user_func($func, $source, $this->photoPath($photo));
        } else {
            imagejpeg($source, $this->photoPath($photo));
        }
        imagedestroy($source);
    }

    function createThumb($filename, Photo $photo, $newwidth = 100, $newheight = 100, $percent = 0.05)
    {
//        /slir/h100/65/2/356_mori.JPG
        $slirPath = $this->slirPath($photo);
        copy("http://153.120.167.173/slir/h200/$slirPath", $this->photoThumbFullPath($photo));

//        header("Content-Type: $photo->mime");
//        list($width, $height) = getimagesize($filename);
//        $newwidth = $newheight / $height * $width;
//        $newwidth = $width < $newwidth ? $width : $newwidth;
//        $newheight = $height < $newheight ? $height : $newheight;
//        $thumb = imagecreatetruecolor($newwidth, $newheight);
//        $func = "imagecreatefrom{$photo->extension}";
//        if (function_exists($func)) {
//            $source = call_user_func($func, $filename);
//        } else {
//            $source = imagecreatefromjpeg($filename);
//        }
//        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
//
//        ///slir/h100/65/2/356_mori.JPG
//        $this->image_fix_orientation($thumb, $filename);
//        $func = "image{$photo->extension}";
//        if (function_exists($func)) {
//            call_user_func($func, $thumb, $this->photoThumbFullPath($photo));
//        } else {
//            imagejpeg($thumb, $this->photoThumbFullPath($photo));
//        }
//        imagedestroy($thumb);
    }

    public function slirPath(Photo $photo)
    {
        return "$photo->user_id/$photo->group_id/{$photo->id}_{$photo->name}";
    }

    function image_fix_orientation(&$image, $filename)
    {
        $image = imagerotate($image, array_values([0, 0, 0, 180, 0, 0, -90, 0, 90])[@exif_read_data($filename)['Orientation'] ?: 0], 0);
    }

    public function photoPath(Photo $photo)
    {
        return $this->photoDir($photo) . $this->photoName($photo);
    }

    public function photoDir(Photo $photo)
    {
        return $this->basePhotoPath("{$photo->user_id}/{$photo->group_id}/");
    }

    public function photoThumbFullPath(Photo $photo)
    {
        return $this->photoDir($photo) . $this->photoThumbName($photo);

    }

    public function photoName(Photo $photo)
    {
        return "{$photo->id}_{$photo->name}";
    }

    function photoThumbName(Photo $photo)
    {
        return "{$photo->id}_thumb_{$photo->name}";
    }

    function isThumbExists(Photo $photo)
    {
        return file_exists($this->photoThumbFullPath($photo));
    }

    public function basePhotoPath($path = null)
    {
        return storage_path('app/photos/' . ($path ? $path : ''));
    }
}
