<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelFollow\Traits\CanBeVoted;
use Modules\User\Entities\Sentinel\User;

class ProductReviewReply extends Model
{
    use CanBeVoted;
    protected  $table = 'product_comments_reply';
    protected $guarded = [];

    public function review()
    {
        return $this->belongsTo(OrderReview::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class,'to_user_id');
    }

    public function markAsRead()
    {
        if(is_null( $this->read_at )){
            $this->forceFill([
                'read_at' => $this->freshTimestamp(),
                'has_read' => 'T'
            ])->save();
        }
    }

    //由于collection没有markAsRead方法，故我们自定义增加一个MessageCollection类
    public function newCollection(array  $models=[])
    {
        return new MessageCollection($models);
    }

    //私信标识已读
    public function read()
    {
        return $this->has_read === 'T';
    }

    public function unread()
    {
        return $this->has_read === 'F';
    }
    public function shouldAddUnreadClass()
    {
        //标记自己收到的信是否已读
        if( user()->id === $this->to_user_id ){
            return $this->has_read;
        }
        return $this->unread();
    }

    public function orderReview()
    {
        return $this->belongsTo(OrderReview::class,'product_comment_id');
    }
    
}
