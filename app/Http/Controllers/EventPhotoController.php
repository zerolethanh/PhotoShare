<?php

namespace App\Http\Controllers;

use App\Event;
use App\Photo;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EventPhotoController extends Controller

{

    protected $user;
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->user = Auth::user();
    }

    //events/$eventId/photo
    public function index(Request $request, $eventId)
    {
        $event = $this->user->events()->withPivot('admin')->findOrFail($eventId);
        $user = $this->user;

        if ($request->has('mobile')) {
            $links = $this->photoLinks($event);

            $byself = $request->session()->get('byself');
            $byshared = $request->session()->get('byshared');

            if (is_null($byself) || is_null($byshared)) {
                $byself = $event->pivot->admin ? 1 : 0;
                $byshared = $byself ? 0 : 1;

            }
            $events = $byself ? $this->user->adminEvents() : $this->user->sharedEvents();
            $events = $events->descId()->take(20)->get();
            $return_data = compact('links', 'event', 'events', 'byself', 'byshared', 'user');

            return $return_data;
        }

        return view('photo.real.mainpage2', compact('event', 'user'));
    }

    public function photoLinks($event)
    {
        $photoIds = $event->photos()->orderBy('id', 'desc')->lists('id');//id list
        $event_id = $event->id;

        $links = $photoIds->map(function ($photoId) use ($event_id) {
            return "/event/$event_id/photo/$photoId";
        });

        return $links;
    }

    public function show(Request $request, $eventId, $photoId)
    {
        $photo = $this->user->events()
            ->findOrFail($eventId)
            ->photos()
            ->findOrFail($photoId);

//        $photo = Photo::findOrFail($photoId);
        if ($photo) {
            if ($request->thumb) {
                if ($this->isThumbExists($photo)) {
                    $this->showImageDirectToBrowser($this->photoThumbFullPath($photo));
                }
            }
            $this->showImageDirectToBrowser($this->photoPath($photo));
        }
        return 'no photo exists';
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

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $eventId)
    {
        //
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
}


