<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\Sentinel\User;

class Comment extends Model
{
    protected $table = 'comments';
    protected $guarded = [];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function replies()
    {
        return $this->hasMany(self::class , 'pid');
    }
    
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
