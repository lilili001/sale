<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderRefund extends Model
{
//    const TABLE = 'order_refund';
//    protected $table = self::TABLE;
    protected $table = 'order_refund';
    protected $fillable = [];

    //退款记录的交流
    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }
}
