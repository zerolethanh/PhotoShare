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
        if (!$request->user()) {
            return redirect('auth/login');
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
