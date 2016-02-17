<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class htmlController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest');
    }

    public function index(Request $request)
    {
        if ($request->user()) {
            //if web
            $lastEvent = $request->user()->allAdminEvents()->orderBy('id', 'desc')->first();

            if ($lastEvent) {
//            return redirect("/event/$lastEvent->id/photo")->with(['byself' => 1, 'byshared' => 0]);
                return (new EventPhotoController())->index($request, $lastEvent->id);
            } else {
                return redirect('/photos/create');
            }
        } else {
            return redirect('login');
        }

    }

    public function home()
    {
//        return view('welcome');

    }

    public function pusher()
    {
        return view('html.pusher');
    }
}
