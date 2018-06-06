<?php

namespace Modules\Sale\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use Translatable;

    protected $table = 'comments';
    protected $guarded = [];

    public function commentable()
    {
        return $this->morphTo();
    }
}
