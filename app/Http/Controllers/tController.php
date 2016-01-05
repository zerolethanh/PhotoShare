<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App;
//use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Mail;

class tController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function getT()
    {
        phpinfo();
//        phpversion();
    }

    public function getNew()
    {
        return 'new';
    }

    public function getEnv()
    {
//        $environment = App::environment();
//        return $environment;
        return app()->environment();
    }

    public function getTz()
    {
        config(['app.timezone' => 'Asia/Ho_chi_minh']);
        return date('Y-m-d H:i:s(\U\T\CP)');
    }

    public function getAppv()
    {
        return app()->version();
    }

    public function getMail()
    {
        return readfile(storage_path('app/schedule.txt'));
//        return Mail::send('emails.test', [], function ($message) {
//            $message->from('lvt3702@gmail.com')->to('zero.lethanh@gmail.com');
//        });
    }

    public function getEmailSends()
    {
//        return event(new )
    }

    public function getInsertUser(Request $request)
    {
        return App\User::create([
            'name' => 'le van thanh',
            'email' => 'lvt3702@gmail.com',
            'password' => bcrypt('zero')
        ]);
    }

    public function getPusher()
    {
        return view('html.pusher');
    }

    public function getVersion()
    {
        return App::version();
    }

    public function getAngular()
    {
        return view('angular.home');
    }

    public function getCreatePhotos(Request $request)
    {
        $created = 0;
        DB::beginTransaction();
        foreach (App\User::all() as $u) {
            for ($i = 0; $i < $request->num; $i++) {
                $u->photos()->create(['group_id' => mt_rand(1, 20)]);
                ++$created;
            }
        }
        DB::commit();
        return $created;
    }
}
