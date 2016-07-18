<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

//    public $isMobile;

//    public $isDebug;

//    public function __construct()
//    {
//        $this->isMobile = request()->has('mobile');

//        $debug = request('d', false);
//        $this->isDebug =
//            $debug && (request()->user()->id == 9);
//    }
}
