<?php

namespace Modules\Sale\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Product;
use Overtrue\LaravelFollow\Traits\CanBeVoted;

class OrderReview extends Model
{
    use CanBeVoted;
    protected  $table = 'product_comments';
    protected $guarded = [];

    public function replies()
    {
        return $this->hasMany(ProductReviewReply::class ,'product_comment_id' );
    }
}
