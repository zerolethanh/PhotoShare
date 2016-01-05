<?php

namespace App\Http\Controllers;

use App\Events\UserLogin;
use App\Jobs\SendReminderEmail;
use App\Photo;
use App\User;
use Carbon\Carbon;
use Endroid\QrCode\QrCode;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use App\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $user;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->user = Auth::user();
    }

    public function getEvent(Request $request)
    {
        event(new UserLogin($this->user));
    }

    public function getInfo(Request $request)
    {
        if ($request->user()) {
            return $request->user();
        }
        return 'need login';
    }


    public function getId(Request $request, $id)
    {
        $user = $request->user() ?: User::find($id);
        return $user ?: 'not found';

    }

    public function getName(Request $request, $name)
    {
        $user = $request->user() ?: User::where(['name' => $name])->first();
        return $user ?: 'not found';
    }

    public function id(Request $request, $id)
    {
        return $this->getId($request, $id);
    }

    public function getEmail(Request $request, $id)
    {
        $user = $request->user() ?: User::find($id);
        if ($user) {
            return $user->email;
        }
        return 'user not found';
    }

    public function getEmailsSend(Request $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            $exitcode = Artisan::call('emails:send', ['user' => $user->id]);
            return 'exit code ' . $exitcode;
        }
        return 'user not found';
    }

    public function getReminder(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $updated = $user->update(['last_reminder_token' => str_random(100)]);
        if ($updated) {
            $job = (new SendReminderEmail($user));
            $this->dispatch($job);
        }
    }

    public function getLogin(Request $request)
    {
        return view('auth.login');
    }

    public function getHome()
    {
        return view('html.home', ['user' => $this->user]);
    }

    public function getAllEvents()
    {
        return $this->user->allEvents;
    }

    public function getAddevent($eventId)
    {
        return $this->user->allEvents()->attach($eventId);
    }

    public function getPhotos()
    {
        $or_time = Carbon::now()->format('Y-m-d H:i:s');
        foreach (User::all() as $u) {
            $groups = $u->photos()->lists('group_id')->unique()->values();
            foreach ($groups as $gid) {
                $event = Event::create([
                    'user_id' => $u->id,
                    'event_name' => str_random(),
                    'all_day' => 1,
                    'or_time' => $or_time,
                    'uuid' => Uuid::uuid(),
                ]);
                $u->allAdminEvents()->attach($event->id);
                Photo::where(['user_id' => $u->id, 'group_id' => $gid])->update(['event_id' => $event->id]);
            }
        }
        $q = \utils::queryInfos();
        return compact('q');

//        $photos = $this->user->photos;
//        $event_ids = $this->user->adminEvents()->lists('id');
//        dd($event_ids->random());
//        foreach ($this->user->photos as $p) {
//            $p->update(['event_id' => $event_ids->    random()]);
//        }
//        $query = \utils::queryInfos();
//        return compact('query', 'users');
    }

    public function getMaxEventId()
    {
        foreach (User::all() as $user) {
            $user->update(['last_event_id' => $user->allAdminEvents()->max('event_id')]);
        }
    }

    public function getEventPhotos(Request $request)
    {
        $eventPhotos = $this->user->eventPhotos()->where('photos.event_id', $request->event_id)->get();
        $query = \utils::queryInfos();

        return compact('query', 'eventPhotos');
    }

//    public function getQrcode()
//    {
//        $this->user->qrSave('http://www.welapp.net/user');
//    }

//    public function getJoinEvent(Request $request, $eventId = null)
//    {
//
//    }

//    public function getEncryptEvents()
//    {
//        $events = Event::all();
//
//        foreach ($events as $e) {
//            $e->fill(
//                ['shared_id' => Crypt::encrypt($e->id)]
//            )->save();
//        }
//        return $events;
////        dd($events);
//    }

//    public function getQrpath($path)
//    {
//        return $this->user->qrEventsPath();
//    }

//    public function getLoginNameReset()
//    {
//        $users = User::all();
//        $updated = 0;
//        foreach ($users as $u) {
//            $updated += $u->update(['login_name' => str_random(32)]) ? 1 : 0;
//        }
//        return $updated;
//    }


//debug
//    public function getAll()
//    {
//        $users = User::with(['events' => function ($query) {
//            $query->orderBy('id', 'desc');
//        }])->get();
//        dd($users);
//        foreach ($users as $user) {
//            $user
//        }

//        $users = User::all();
//        foreach ($users as $user) {
//            dd($user);
//            $eventids = $user->events->lists('id');
//            dd($eventids->values()->toArray());

//            $user->allEvents()->attach($eventids->toArray());
//            $user->update(['created_event_ids' => $eventids]);
//        }
//        $query = \utils::queryInfos();
//        return compact('query', 'users');

//        User::all()->each(function(User $user){
//            $events = $user->events()->orderBy('created_at','desc')->get();
//            foreach()
//        });
//    }

//    public function getEvents()
//    {
//        return $this->user->allEvents;
//        $events = Event::orderBy('id', 'desc')->get()->each(function($event){
//            $members = $event->members;
//            foreach($members as $m){
//
//            }
//        });
//        return $events;
//    }
//    public function basePhotoPath($path = null)
//    {
//        return storage_path('app/photos/' . ($path ? $path : ''));
//    }
    public function anyControlToggle(Request $request)
    {
        //client : POST blueimp_gallery_controls_toggle()

//        $redirect_event = $request->input('redirect_event');
        $this->user->blueimp_gallery_controls = !$this->user->blueimp_gallery_controls;
        $ok = $this->user->save();
        return compact('ok');

    }
}
