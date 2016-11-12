<?php

namespace App\Providers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Queue;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //

//        Queue::failing(function ($connection, $job, $data) {
//            // Notify team of failing job...
//            $failing = 'Queues failing.' . $connection . $job;
//            Mail::raw($failing, function ($m) {
//                $m->to('zero.lethanh@gmail.com');
//            });
//        });


    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //

//        write_debug_backtrace(true);
    }
}
