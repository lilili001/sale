<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\Sentinel\User;

class Comment extends Model
{
    /*普通品论 商品咨询 退款退货交流沟通*/
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
