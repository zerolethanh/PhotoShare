<?php

namespace App\Listeners;

use App\Events\UserLogin;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserLogin $event
     * @return void
     */
    public function handle(UserLogin $event)
    {
        $emailView = 'emails.user_login';
        Mail::send($emailView, ['user' => $event->user], function (Message $m) use ($event) {
            $m->subject($event->user->name)
                ->to($event->user->email);
        });
        //        Artisan::call('emails:send', ['user' => $event->user->id]);
    }
}
