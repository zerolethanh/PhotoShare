<?php

namespace App\Http\Controllers;

use App\Event;
use App\EventGroupBy;
use App\Events\NewComment;
use App\EventTag;
use App\User;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class EventController extends Controller
{

    protected $user;

    protected $startTimeKey = 'start_time';
    protected $endTimeKey = 'end_time';
    protected $eventNameKey = 'event_name';
    protected $allDayKey = 'all_day';
    protected $orTimeKey = 'or_time'; // not using

    protected $photos;
    protected $event;
    protected $events;
    protected $events_max_take = 50;
    protected $control;
    protected $eventGroupBy;//for $this->buttons();

    protected $users;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');

        $this->user = request()->user();

        $this->control = isset(request()->user()->blueimp_gallery_controls) ? request()->user()->blueimp_gallery_controls : false;
    }

    /*
    * route: /event
    */
    public function index()
    {
        return $this->getByself();
    }

    /*
     * route: /events/byself
     */
    public function getByself()
    {
        $lastEvent = $this->user->allAdminEvents()->orderBy('id', 'desc')->first();

        if ($lastEvent) {
            return redirect("/event/$lastEvent->id/photo")->with(['byself' => 1, 'byshared' => 0]);
        }
        return redirect('/photos/create');
    }

    /*
     * GET POST /events/photos/$event_id
     */
    public function anyPhotos(Request $request, $event_id)
    {
        $this->event = $event = $request->user()->events()
            ->withPivot('admin')
            ->with(['users' => function ($q) {
                $q->select('users.id', 'name');
            }])
            ->findOrFail($event_id);

        $this->eventGroupBy = $request->user()->eventGroupBy()->where('event_id', $event->id)->first();

        DB::beginTransaction();

        if (isset($this->eventGroupBy)) {
            $group_by = request('group_by', $this->eventGroupBy->group_by);

            if ($group_by != $this->eventGroupBy->group_by) {

                $update = ['user_id' => $request->user()->id,
                    'event_id' => $event->id,
                    'group_by' => $group_by,
                    'description' => EventGroupBy::$groupBy[$group_by]
                ];
                $this->eventGroupBy->update($update);
            }
        } else {
            if (!$this->eventGroupBy) {
                $this->eventGroupBy = new EventGroupBy(
                    ['user_id' => $request->user()->id,
                        'event_id' => $event->id,
                        'group_by' => 'All',
                        'description' => 'All'
                    ]
                );
                $this->eventGroupBy->save();
            }
        }

        DB::commit();

        $this->photos = $photos = $this->photos();

        if ($this->isMobile()) {
            $links = collect($photos['links'])->map(function ($link) {
                return url($link);
            });
        }

        $html['buttons'] = $this->buttons();

        $html['other_albums'] = $this->other_albums();

        $photoHTML = $this->photoHTML();


        $blueimp_gallery = view('html.galleryctl')->render();

        $this->event->pivot->admin = boolval($this->event->pivot->admin);


        return compact('links', 'photos', 'html', 'blueimp_gallery', 'event', 'photoHTML', 'eventGroupBy');
    }

    /*
     * called from anyPhotos(Request $request, $event_id)
     */
    public function photos()
    {
        $all = $this->photos = $this->event->photos()
            ->with([
                'user' => function ($q) {
                    $q->select('id', 'name');
                }
            ])
            ->orderBy('id', 'desc')->get();

        $ids = collect($this->photos)->lists('id');

        $links = $ids->map(function ($photoId) {
            return "/event/{$this->event->id}/photo/$photoId";
        });

        $auth_id = auth()->id();

        $can_be_deleted = collect($this->photos)->map(function ($photo) use ($auth_id) {
            return ['photo_id' => $photo->id,
                'deletable' => $auth_id == $photo['user_id'] ? 'true' : 'false'];
        })->groupBy('deletable');

        if (isset($can_be_deleted['false'])) $can_be_deleted['false'] = collect($can_be_deleted['false'])->pluck('photo_id');
        if (isset($can_be_deleted['true'])) $can_be_deleted['true'] = collect($can_be_deleted['true'])->pluck('photo_id');


//        if ($this->isDebug) {

        $by = $this->photos->groupBy('user_id')->toArray();

        $users = [];

        foreach ($by as $user_id => $photos) {

            $users[] = $photos[0]['user'];

        }

        $users = collect($users)->keyBy('id');

        $this->users = $users;

        return compact('users', 'by', 'links', 'ids', 'can_be_deleted', 'all');
    }



    function photoHTML()
    {
        $links = [];
        $ids = [];

        switch ($this->eventGroupBy->group_by) {
            case 'ByUser':
                foreach ($this->users as $user) {

                    $user_id = $user['id'];
                    $ids[] = $link_user_id = "links_$user_id";

                    $html = "<div id='$link_user_id' class='links'>
                        by: {$user['name']}
                     <br>";

                    $user_photos = $this->photos['by'][$user_id];

                    foreach ($user_photos as $photo) {

                        $link = "/event/{$this->event->id}/photo/{$photo['id']}";

                        $html .=
                            "<a href='$link' title='{$photo['ori_name']}'>
                        <img src='$link?thumb=1' class='img img-rounded' style='margin-top: 5px;' height='100'/>
                    </a>";
                    }

                    $html .= "</div>";

                    $links[] = $html;
                }

                $links = implode('', $links);

                break;
            case 'ByUploadedTime':
                break;
            default://All

                $ids = ['links'];

                $html = "<div id='links' class='links'>";

                foreach ($this->photos['all'] as $photo) {
                    $link = "/event/{$this->event->id}/photo/{$photo->id}";
                    $html .=
                        "<a href='$link' title='{$photo['ori_name']}'>
                        <img src='$link?thumb=1' class='img img-rounded' style='margin-top: 5px;' height='100'/>
                    </a>";

                }

                $html .= "</div>";
                $links[] = $html;


                break;
        }


        return compact('ids', 'links');
    }


    //get buttons html
    public function buttons()
    {
        $is_admin = $this->event->pivot->admin;
        $event = $this->event;

        $this->events = $events = $this->user->events()
            ->admin($is_admin)
            ->select('events.id', 'event_name', 'or_time')
            ->descId()->take($this->events_max_take)->get();

        $compactEvent = compact('event');

        $html[] = "<div class='dropdown pull-left' aria-haspopup='true' style='margin: 4px'>";
        $html[] = view('html.eventsDropDownMenu', [
            'events' => $events,
            'event' => $event,
            'events_max_take' => $this->events_max_take
        ]);
        $html[] = view('html.group_by', ['eventGroupBy' => $this->eventGroupBy]);
        $html[] = "</div>";

        $html[] = '<div class="pull-right">';
        if ($is_admin) {
            $html[] = view('html.adminControlButtons', $compactEvent);
        }
        $html[] = '</div>';

        return implode('', $html);//to string
    }

    public function other_albums()
    {
        if (!$this->events) $this->photos_links();

        $html = '';

        foreach ($this->events as $e) {
            $html .= view('html.other_albums_buttons', ['e' => $e]);
        }

        $html .= view('html.viewer_setting_button', ['control' => $this->control]);

        return $html;
    }


    public function anyLastSelfEvent(Request $request)
    {
        return $this->dropDownHtml(1, $request->event_id);
    }

    public function anyLastSharedEvent(Request $request)
    {
        return $this->dropDownHtml(0, $request->event_id);
    }

    public function dropDownHtml($is_admin = 1, $event_id = null)
    {
        $events = $this->user->events()
            ->withPivot('admin')
            ->admin($is_admin)
            ->descId()->take(1)->get();
        if (!count($events)) {
            return ['no events'];
        }
        if (is_null($event_id)) {
            $event = $events[0];
        } else {
            $event = $this->user->events()->findOrFail($event_id);
        }
        $event->pivot->admin = boolval($event->pivot->admin);
        return compact('event', 'html');
    }


    // GET, POST  /events/create
    public function anyCreate(Request $request)
    {
        $this->validate($request, [$this->eventNameKey => 'required']);

        $dbdata = $request->only(
            $this->eventNameKey, $this->allDayKey, $this->orTimeKey, $this->startTimeKey, $this->endTimeKey, 'platform');

        if (!isset($dbdata['platform'])) {
            if ($request->has('mobile')) {
                $platform = 'mobile';

            } else {
                $platform = 'web';
            }

            $dbdata['platform'] = $platform;
        }
        $newEventData = array_merge(
            $dbdata, ['created_by' => $this->user->id, 'uuid' => Uuid::uuid()]
        );
        $event = $this->user
            ->events()
            ->create($newEventData, ['joined_at' => date('Y-m-d H:i:s')]);

        if ($event) {
            $event->fill(['shared_id' => Crypt::encrypt($event->id), 'user_id' => $this->user->id])->save();
            $this->user->qrSaveEvent($event);

            if ($request->has('mobile')) {
                return $event;
            }
            return redirect('/photos/upload/' . $event->id);
        }

        return ['can not create event'];
    }

    public function anyAddComment(Request $request, $event_id)
    {
        $this->validate($request, ['body' => 'required']);

        $event = Event::findOrFail($event_id);
        if (!$event->users()->where('users.id', $this->user->id)->exists()) {
            return ['不正アクセス'];
        }

        $comment = $event->comments()->create([
            'body' => $request->body,
            'by_user' => $this->user->id,
            'pub_at' => Carbon::now(),
            'event_id' => $event->id
        ]);
        event(new NewComment($comment, $event));
        return compact('event', 'comment');
        return $comment;
    }

    public function anyGetComments(Request $request, $event_id)
    {
        $event = $this->user->events()->findOrFail($event_id);

        $comments = $event->comments()->with('user')->get();

        $commentsHtml = '';
        foreach ($comments as $comment) {

            $name = $comment->user->name ?: $comment->user->email;
            $can_be_deleted = $this->user->id == $comment->by_user ? true : false;
            $delButton = '';
            if ($can_be_deleted) {
                $delButton = "<button type='button'
                                      class='close'
                                       onclick='commentDelete($comment->id)'>&times;</button>";//"<button type='button' class='btn btn-sm'>削除</button>";
            }
            $commentBody = "<div style='white-space: pre-wrap;font-size: smaller;'>$comment->body</div>";
            $commentPubAt = "<p style='font-size: x-small;'><b style='color: blue;'>$name</b> $comment->pub_at</p>";
            $blockquoteOpen = "<blockquote style='margin: 3px!important;padding-left: 8px!important;'>";
            $blockquoteClose = "</blockquote>";

            $commentsHtml .= implode('', [$blockquoteOpen, $delButton, $commentBody, $commentPubAt, $blockquoteClose]);

        }

        return compact('event', 'commentsHtml');
    }


    /*
    * route: /events/byshared
    */
    public function getByshared()
    {
        $lastEvent = $this->user->allSharedEvents()->orderBy('id', 'desc')->first();
        if ($lastEvent) {
            return redirect("/event/$lastEvent->id/photo")->with(['byself' => 0, 'byshared' => 1]);
        }
        return $this->getByself();
    }


    public function getInvite(Request $request, $event_id)
    {
        $event = $this->user->allAdminEvents()->findOrFail($event_id);
        \utils::showImageDirectToBrowser($event->qrSave());
    }

    //GET POST /events/qr/$event_id
    public function anyQr(Request $request, $event_id)
    {

        $event = $this->user->adminEvents()->findOrFail($event_id);

        if ($event) {
            $qrURL = $this->user->qrEventsPath($event);

            if ($this->isMobile()) {
                return $qrURL;
            }
            if (!file_exists($qrURL)) {
                \utils::showImageDirectToBrowser($this->user->qrSaveEvent($event));
            }
            \utils::showImageDirectToBrowser($qrURL);
        }
    }

    public function isMobile()
    {
        return request()->has('mobile');
    }

    public function postSendemail(Request $request, $event_id)
    {
        $this->validate($request, ['email' => 'required | email']);

        $event = $this->user->adminEvents()->findOrFail($event_id);

        if ($event) {
            $path = $this->user->getEventJoinPath($event);

//            mail($request->email, 'PhotoShareリンクが届いています。', view("emails.AlbumJoinInvitation", ['path' => $path, 'event' => $event, 'user' => $this->user])->render());
            Mail::send(
                "emails.AlbumJoinInvitation",
                ['path' => $path, 'event' => $event, 'user' => $this->user],
                function ($message) use ($request) {
                    $message->to($request->email)
                        ->subject('PhotoShareリンクが届いています。');
                });
            if ($request->mobile) {
                return compact('event', 'user', 'path');
            }
            return redirect("/event/$event_id/photo");

        }
    }

    public function anyJoin(Request $request, $shared_id = null)
    {

        //既に参加したイベント(参加者, mobileのみ)
        if ($event_id = request('event_id')) {

            $event = $request->user()->events()->find($event_id);

            return ['joined' => true, 'event' => $event];

        }

        //uuid または crypted id で参加する (自分)
        if (strlen($shared_id) == 36) {

            $event = Event::where('uuid', $shared_id)->first();
            if (!$event) {
                return ['joined' => false, 'note' => "イベントが見つかりません。"];
            }
            $event_id = $event->id;

        } else {

            // or Crypted $event_id
            try {
                $event_id = Crypt::decrypt($shared_id);

                $event = Event::findOrFail($event_id);


            } catch (DecryptException $e) {
                return ['joined' => false, 'note' => "イベントが見つかりません。"];
            }
        }

        $isMobile = $request->has('mobile');

        //既に参加した場合
        if ($this->user->events()->where(['events.id' => $event_id])->exists()) {

        } else {
            //これから参加する場合
            $this->user->events()->attach($event_id, ['admin' => 0, 'joined_at' => date('Y-m-d H:i:s')]);
        }

        if ($isMobile) {
            return ['joined' => true, 'event' => $event];
        }
        return redirect("/event/$event_id/photo");


    }

    public function postEdit(Request $request, $event_id)
    {
        $this->validate($request, ['or_time' => 'required', 'event_name' => 'required']);

        $event = $this->user->events()->admin(1)->findOrFail($event_id);

        DB::beginTransaction();
        $update = $event->update($request->only('or_time', 'event_name'));

        if ($tags = $request->tags) {
            $tags = array_filter(explode('#', $tags));
            if (is_array($tags)) {

                $event->tags()->delete();

                $tagsObj = [];
                foreach ($tags as $tag) {
                    $tagsObj[] = new EventTag(["tag" => $tag]);
                }

                $event->tags()->saveMany($tagsObj);

            }
        }
        DB::commit();

        return redirect("/event/$event_id/photo");
    }

    public function postDelete(Request $request, $event_id)
    {
        $this->user->events()
            ->admin()
            ->findOrFail($event_id)
            ->delete();
        return redirect('/event');
    }

    public function postLeave(Request $request, $event_id)
    {
        $leave = Event::id($event_id)->firstOrFail()->users()->detach(Auth::id());
        return compact('leave');

    }

    public function anyMore(Request $request)
    {
        return '実装中...';
    }

    public function anyAdmin()
    {
        return $this->user->events()->admin()->descid()->get();
    }

    public function anyShared()
    {
        return $this->user->events()->shared()->descid()->get();
    }


    public function allEvents()
    {
        $events = $this->user->events()
            ->orderBy('created_at', 'desc')->get();

//        if (request()->user()->id == 9) {
        $events->load(['user' => function ($userQ) {
            $userQ->select('id', 'name');
        }]);
//        }

        return $events;

    }

    public function sharedEvents()
    {
        $events = $this->user->sharedEvents()->get();
        $query = \utils::queryInfos();

        return compact('query', 'events');
    }

    public function adminEvents()
    {
        $events = $this->user->adminEvents()->descId()->get();
        $query = \utils::queryInfos();

        return compact('query', 'events');
    }



//    public function getPhotos(Request $request, $term, $identifier)
//    {
//        $term = ucfirst($term);
//        return $this->{"get{$term}"}($request, $identifier);
//    }

    public function getId(Request $request, $id)
    {
        return Event::find($id)->photos;
    }

    public function getSecret(Request $request, $uuid)
    {
        return Event::where(['uuid' => $uuid])->firstOrFail()->photos;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
    public function store(Request $request)
    {
        $this->user->addEvent();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $fotos = $this->user->photosOfEvent($id)->get();
//        $query = \utils::queryInfos();
        return compact('query', 'fotos');
    }


    public function all()
    {
        return $this->user->events()->descid()->get();
    }

    public function getAll()
    {
        return $this->all();
    }

    public function anySearch(Request $request)
    {
        $this->validate($request, ['event_name' => 'required']);

        $search_text = trim($request->input('event_name'));

        if (starts_with($search_text, '#')) {
            $tag = substr($search_text, 1);
            $events = $request->user()->events()->whereHas('tags', function ($query) use ($tag) {
                $query->where('tag', 'like', "%$tag%");
            })->get();
        } else {
            $events = $this->user->events()
                ->where('event_name', 'like', "%{$search_text}%")->get();
        }

        if ($request->has('mobile')) {
            return compact('events', 'search_text');
        } else {
            $html = view('html.eventSearchResult', compact('events', 'search_text'));
            return $html;
        }
    }

}
