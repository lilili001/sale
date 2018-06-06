<?php

namespace Modules\Sale\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class OrderReturn extends Model
{
    protected $table = 'order_return';
    protected $fillable = [];

    //退货记录的交流
    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }
}
