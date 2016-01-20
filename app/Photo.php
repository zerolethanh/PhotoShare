<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Photo extends Model
{
    //
    use SoftDeletes;

    protected $table = 'photos';
    protected $guarded = ['id'];
    protected $casts = [
        'group_id' => 'int',
        'user_id' => 'int',
        'id' => 'int'
    ];
    protected $dates = ['updated_at', 'created_at'];
    protected $appends = ['title', 'link', 'link_thumb', 'created_at_ja'];

//    protected $dateFormat = 'U';
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function scopeGroup($query, $group_id)
    {
        return $query->where('group_id', $group_id);
    }

    public function scopeLateOrder($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeId($query, $id)
    {
        return $query->where('id', $id);
    }

    public function getTitleAttribute()
    {
        return $this->ori_name ?: $this->name;
    }

    public function getLinkAttribute()
    {
        return url("event/$this->event_id/photo/$this->id");
    }

    public function getLinkThumbAttribute()
    {
        return $this->getLinkAttribute() . "/?thumb=1";
    }

    public function getCreatedAtJaAttribute()
    {
        $created_at = $this->created_at;
        if ($created_at instanceof Carbon) {
            $created_at = $created_at->format('Y年m月d日 H時i分');
        }
        return $created_at;
    }

}
