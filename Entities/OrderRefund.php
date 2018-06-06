<?php

namespace Modules\Sale\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class OrderRefund extends Model
{
    protected $table = 'order_refund';
    protected $fillable = [];

    //退款记录的交流
    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }
}
