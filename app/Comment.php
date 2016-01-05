<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    //
    protected $guarded = ['id'];
    protected $dates = ['pub_at'];

    use SoftDeletes;

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'by_user');
    }

    public function getPubAtAttribute($pub_at)
    {
        return Carbon::parse($pub_at)->format('Y年m月d日 H:h:i');
    }
}
