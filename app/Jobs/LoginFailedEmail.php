<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;


class LoginFailedEmail extends Job implements SelfHandling, ShouldQueue
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $tryLoginEmail;

    public function __construct($tryLoginEmail)
    {
        //
        $this->tryLoginEmail = $tryLoginEmail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        //
        $mailer->send('emails.LoginFailedEmail', [], function (Message $message) {
            $message->to($this->tryLoginEmail)
                ->subject('PhotoShare Login Failed Notification');

        });

    }
}
