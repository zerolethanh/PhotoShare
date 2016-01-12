<?php

namespace App;

use Faker\Provider\Uuid;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Event;

class User extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    protected $table = 'users';

    protected $hidden = ['password', 'remember_token', 'last_reminder_token'];

    protected $guarded = ['id', 'updated_at'];
    protected $casts = [
        'created_event_ids' => 'array',
        'shared_event_ids' => 'array',
        'usable_event_ids' => 'array',
    ];

    protected $dates = ['last_auth'];

    public function photos()
    {
        return $this->hasManyThrough(Photo::class, Event::class, 'user_id', 'event_id');
    }

    public function scopePhotoGroup($query, $id = null)
    {
        if (is_null($id)) {
            return $this->photos();
        }

        return $this->photos()->group($id);
    }

    public function eventGroupBy()
    {
        return $this->hasMany(EventGroupBy::class);
    }

    public function scopePhotosOfEvent($query, $event_id)
    {
        return $this->eventPhotos()->where('photos.event_id', $event_id);
    }

    public function eventPhotos()
    {
        return $this->hasManyThrough(Photo::class, Event::class, 'user_id', 'event_id');
    }


    public function addEventFromRequest($request = null)
    {
        $request = $request ?: Request::capture();
        $data = call_user_func_array([$request, 'only'], Event::columnListing());

        $data['uuid'] = Uuid::uuid();

        $data = array_only($data, Event::columnListing());

        DB::beginTransaction();
        $newEvent = $this->events()->create($data);
        if ($newEvent) {
            $newEvent->fill(['shared_id' => Crypt::encrypt($newEvent->id)])->save();
            $this->allEvents()->attach($newEvent->id, ['admin' => 1]);
        }
        DB::commit();

        return $newEvent;
//        return compact('newEvent');
    }

    public static function columnListing()
    {
        return Schema::getColumnListing('users');
    }

    public function addEvent($request = null)
    {
        return $this->addEventFromRequest($request);
    }

    public function event($eventId)
    {
        return $this->events()->findOrFail($eventId);
    }

    public function photo($photoId)
    {
        return $this->photos()->findOrFail($photoId);
    }


    public function events()
    {
        return $this->belongsToMany(Event::class, 'user_event', 'user_id', 'event_id');
    }

    public function allEvents()
    {
        return $this->belongsToMany(Event::class, 'user_event', 'user_id', 'event_id');
    }

    public function scopeAdminEvents()
    {
        return $this->allEvents()->where('user_event.admin', 1)->withPivot('admin');
    }

    public function scopeSharedEvents()
    {
        return $this->allEvents()->where('user_event.admin', 0)->withPivot('admin');
    }


    public function scopeAllAdminEvents($query)
    {
        return $this->allEvents()->where('user_event.admin', 1);
    }

    public function scopeAllSharedEvents($query)
    {
        return $this->allEvents()->where('user_event.admin', 0);
    }

    public function scopeAdminOnOffEventsQuery($q, $admin, $orderBy = ['id', 'desc'], $take = 10)
    {
        return $this->allEvents()->where('user_event.admin', $admin)->orderBy($orderBy[0], $orderBy[1])->take($take);
    }

    public function scopeAdminOnEvents($q, $orderBy = ['id', 'desc'], $take = 10)
    {
        return $this->scopeAdminOnOffEventsQuery($q, 1, $orderBy, $take);
    }

    public function scopeAdminOffEvents($q, $orderBy = ['id', 'desc'], $take = 10)
    {
        return $this->scopeAdminOnOffEventsQuery($q, 0, $orderBy, $take);
    }

    public function scopeAdminOnLast10Events($q, $orderBy = ['id', 'desc'])
    {
        return $this->scopeAdminOnOffEventsQuery($q, 1, $orderBy, 10);
    }

    public function scopeAdminOffLast10Events($q, $orderBy = ['id', 'desc'])
    {
        return $this->scopeAdminOnOffEventsQuery($q, 0, $orderBy, 10);
    }

    public function allEventsLateOrder($by = 'id')
    {
        return $this->allEvents()->orderBy($by, 'desc')->withPivot('admin')->get();
    }


    public function photoEvent($eventId, $photoId)
    {
        return $this->allEvents()->findOrFail($eventId)->photos()->findOrFail($photoId);
    }

    //photos
    public function basePhotoPath($path = null)
    {
        $path = storage_path("app/photos/$this->id/" . ($path ? $path : ''));

        if (!file_exists($path)) {
            $created = \utils::createFileOrDirIfNotExists($path);
            if (!$created) {
                throw new \Exception;
            }
        }
        return $path;
    }

    public function photoPath($path = null)
    {
        return $this->basePhotoPath($path);
    }


    public function qrSave($text, $fullPath = null, $label = null, $size = 200)
    {
        $fullPath = $fullPath ?: $this->userPath() . Uuid::uuid() . ".png";
        $qrCode = new \Endroid\QrCode\QrCode();
        $qrCode
            ->setText($text)
            ->setSize($size)
            ->setPadding(5)
            ->setErrorCorrection('high')
            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0));

        if ($label) {
            $qrCode
                ->setLabel($label)
                ->setLabelFontSize(16);
        }
        $img = $qrCode->getImage();
        $succ = imagepng($img, $fullPath);
        if (!$succ) die('can not save QrCode for ' . $fullPath);
        imagedestroy($img);
        return $fullPath;
    }

    public function qrSaveEvent($event)
    {
        $fullPath = $this->qrEventsPath($event);
        if ($event->uuid) {
            $eventJoinPath = url("/events/join/$event->uuid");
        } else {
            $eventJoinPath = url("/events/join/$event->shared_id");
        }
        return $this->qrSave($eventJoinPath, $fullPath);
    }

    public function getEventJoinPath($event)
    {
        $eventJoinPath = url("/events/join/$event->shared_id");
        return $eventJoinPath;
    }

    public function qrEventsPath(Event $event)
    {
        return $this->userPath("qr/events/") . "$event->id.png";
    }

    public function userPath($path = null)
    {
        $path = storage_path("app/users/$this->id/" . ($path ? $path : ''));
        if (!file_exists($path)) {
            $created = \utils::mkdir($path);
            if (!$created) die("$path NOT created");
        }
        return $path;
    }

//    public function comments()
//    {
//        return $this->hasManyThrough(Comment::class, Event::class);
//    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'by_user');
    }
}
