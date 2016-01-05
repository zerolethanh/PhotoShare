<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\CommentDeleted;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    protected $user;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->user = Auth::user();
    }

    public function anyDelete(Request $request, $comment_id)
    {

        $comment = $this->user->comments()->findOrFail($comment_id);

        if ($comment->by_user == $request->user()->id) {

            $event = $comment->event;

            $deleted = $comment->delete();
            event(new CommentDeleted($event));
            return compact('deleted');
        }

        return ['不正アクセス'];
    }

}
