<?php

namespace App\Events;

use App\Comment;
use App\Events\Event;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

//use App\Event;

class NewComment extends Event implements ShouldBroadcast
{
    use SerializesModels;
    use InteractsWithQueue;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $comment;
    public $event;

    public function __construct(Comment $comment, \App\Event $event)
    {
        //
        $this->comment = $comment;
        $this->event = $event;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['has_new_comment'];//redis
//        return ['album'];
    }

    /**
     * Get the broadcast event name.
     *
     * @return string
     */
//    public function broadcastAs()
//    {
//        return 'has_new_comment';
//    }
}
