<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\Sentinel\User;

class ProductComment extends Model
{
    protected $table = 'product_comments';
    protected $guarded = [];
}
