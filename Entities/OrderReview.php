<?php

namespace Modules\Sale\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Product;

class OrderReview extends Model
{
    protected  $table = 'product_comments';
    protected $guarded = [];

    public function replies()
    {
        return $this->hasMany(ProductReviewReply::class ,'product_comment_id' );
    }
}
