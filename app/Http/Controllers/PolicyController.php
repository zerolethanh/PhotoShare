<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PolicyController extends Controller
{
    //
    public function getPrivacy()
    {
        return view('policies.privacy');
    }

    public function getTerms()
    {
        return view('policies.terms');
    }
}
