<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class Event extends Model
{
    use SoftDeletes;
    //
    protected $guarded = ['id', 'created_at'];
    protected $table = 'events';
    protected $dates = ['or_time'];

//    protected $casts = [
//        'members' => 'array'
//    ];

    protected $appends = ['or_time_ja_jp', 'members'];

//    protected $hidden = ['shared_id'];

    public function photosUuid()
    {
        return $this->hasMany(Photo::class, 'event_uuid', 'uuid');
    }

    public function photos()
    {
        return $this->hasMany(Photo::class, 'event_id', 'id');
    }

    public function photo($photoId)
    {
        return $this->photos()->findOrFail($photoId);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function allUsers()
    {
        return $this->belongsToMany(User::class, 'user_event', 'event_id', 'user_id');
    }

    public function users()
    {
        return $this->allUsers();
    }

    public static function columnListing()
    {
        return Schema::getColumnListing('events');
    }

    public function scopeId($query, $eventId)
    {
        return Auth::user()->events()->where('events.id', $eventId);
    }

    public function scopeDescId($query)
    {
        return $query->orderBy('id', 'desc');
    }

    public function qrSave($fullSaveImagePath = null)
    {
        $user = Auth::user();
        $fullSaveImagePath = $fullSaveImagePath ?: ($user->qrEventsPath() . "{$this->id}.png");
        $eventJoinPath = url("/event/join/$this->shared_id");
        return $user->qrSave($eventJoinPath, $fullSaveImagePath);
    }

    public function scopeAdmin($query, $is_admin = 1)
    {
        return $query->where('user_event.admin', $is_admin);
    }

    public function scopeShared($query)
    {
        return $query->where('user_event.admin', 0);
    }

    public function getOrTimeAttribute($time)
    {
        return Carbon::parse($time)->format('Y-m-d');
    }

    public function getOrTimeJaJpAttribute()
    {
        $ja_jp = Carbon::parse($this->or_time)->format('m月d日');
        return $ja_jp;
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public static function downloadZipPhotos($files = array(), $filenames = array(), $event_name = 'photos')
    {
        $valid_files = array();
        //if files were passed in...
        if (is_array($files)) {
            //cycle through each file
            foreach ($files as $file) {
                //make sure the file exists
                if (file_exists($file)) {

                    $valid_files[] = $file;
                }
            }
        }

//if we have good files...
        if (count($valid_files)) {
            //create the archive
            $zip = new \ZipArchive();

            # create a temp file & open it
            $tmp_file = tempnam('.', '');
            $zip->open($tmp_file, \ZipArchive::CREATE);

            //add the files
            foreach ($valid_files as $idx => $file) {
                $name = $file;
                if (isset($filenames[$idx])) {
                    $name = $filenames[$idx];
                }
                $zip->addFile($file, $name);
            }
            //debug
            //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;

            //close the zip -- done!
            $zip->close();

            return response()->download($tmp_file, "$event_name.zip");
//            header('Content-disposition: attachment; filename=download.zip');
//            header('Content-type: application/zip');
//            readfile($tmp_file);
        } else {
            return [false];
        }
    }

    public function getSharedIdAttribute($shared_id)
    {
        if ($this->user_id == Auth::id()) {
            return $shared_id;
        }
    }

    public function tags()
    {
        return $this->hasMany(EventTag::class);
    }

    public function getMembersAttribute()
    {
        if (count($users = $this->users)) {
            unset($this->users);
            return implode(' , ', collect($users)->pluck('name')->filter()->values()->unique()->all());
        }
        return '';
    }

    public function eventGroupBy()
    {
        return $this->hasMany(EventGroupBy::class);
    }

    public function getUuidAttribute($uuid)
    {
        if (!isset($uuid)) {
            return null;
        }
        //if event.user_id == request user id then show uuid
        if (isset($this->user_id) && isset(request()->user()->id)) {
            if ($this->user_id == request()->user()->id) {
                return $uuid;
            }
        }

        return null;
    }

}
