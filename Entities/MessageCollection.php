<?php
/**
 * Created by PhpStorm.
 * User: yixin
 * Date: 2018/6/26
 * Time: 13:52
 */

namespace Modules\Sale\Entities;


use Illuminate\Database\Eloquent\Collection;

class MessageCollection extends Collection
{
    public function markAsRead()
    {
        $this->each(function($message){
            if( $message->to_user_id === user()->id ){
                $message->markAsRead();
            }
        });
    }
}