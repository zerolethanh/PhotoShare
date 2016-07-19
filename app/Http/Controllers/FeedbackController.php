<?php

namespace App\Http\Controllers;

use App\Feedback;
use Illuminate\Http\Request;

use App\Http\Requests;

class FeedbackController extends Controller
{
    //

    public function index()
    {
        $feedbacks = Feedback::latest()->paginate(5);
        return view('feedback.index', compact('feedbacks'));
    }

    public function store(Request $request)
    {
        $this->validate($request,
            [
                'you_want' => 'required',
            ]
        );
        $feedback = Feedback::create($request->all());
//        return $request->all();
        return back();
    }
}
