<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HomepageController extends Controller
{
    //
    public function __construct()
    {
        //if not login yet then go homepage page
        $this->middleware(['homepage']);
    }

    public function index()
    {

        return view('homepage');
    }
}
