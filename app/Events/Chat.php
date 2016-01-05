<?php

namespace App\Events;

use App\Events\Event;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Auth;

class Chat extends Event implements ShouldBroadcast
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $to_user;
    public $message;
    public $from_user;

    public function __construct(User $to_user, $message)
    {
        //
        $this->to_user = $to_user;
        $this->from_user = Auth::user();

        $this->message = $message;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        //送信データ: (全てpublic属性変数データー)
//        {
//            "to_user": {
//              "id": 1,
//              "name": "le van thanh"
//            },
//          "from_user": {
//              "id": 2,
//              "name": "nakagawa"
//            },
//          "message": "test"
//         }
        return ['to_user_id.' . $this->to_user->id];
    }
}
