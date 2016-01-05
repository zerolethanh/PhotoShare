<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send {user? : User ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $userid = $this->argument('user') ?: 9;//zero.lethanh@gmail.com
        $user = User::findOrFail($userid);

        $contents = file_get_contents(storage_path('app/schedule.txt'));
        return Mail::raw($contents, function ($message) use ($user) {
            return $message->to($user->email)->subject('from Sendmails');
        });
    }
}
