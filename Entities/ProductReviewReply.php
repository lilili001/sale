<?php

namespace Modules\Sale\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Product;
use Overtrue\LaravelFollow\Traits\CanBeVoted;

class ProductReviewReply extends Model
{
    use CanBeVoted;
    protected  $table = 'product_comments_reply';
    protected $guarded = [];
    public function review()
    {
        return $this->belongsTo(OrderReview::class);
    }
}
