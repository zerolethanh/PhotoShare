<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class EventTagController extends Controller
{
    public function getTags(Request $request)
    {

    }

    public function index(Request $request, $eventId)
    {
        return implode('',array_map(function ($val) {
            return "#" . $val;
        }, $request->user()->events()->findOrFail($eventId)->tags()->lists('tag')->toArray()));

    }

    public function show(Request $request, $eventId, $tagId)
    {
        return $request->user()->events()->findOrFail($eventId)->tags()->find($tagId);
    }
}
