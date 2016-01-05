<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventGroupBy extends Model
{
    //
    protected $table = "event_group_by";
    protected $guarded = ['id'];

//    public $timestamps = false;

    public static $groupBy = [
        'All' => 'All',
        'ByUser' => 'By User',
        'ByUploadedTime' => 'By Uploaded Time'
    ];
}
