<?php

namespace Modules\Sale\Repositories\Eloquent;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Sale\Entities\ProductReviewReply;
use Modules\Sale\Notifications\CommentReplyNotification;
use Modules\Sale\Repositories\OrderReviewRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

/**
 * Class EloquentOrderReviewRepository
 * @package Modules\Sale\Repositories\Eloquent
 */
class EloquentOrderReviewRepository extends EloquentBaseRepository implements OrderReviewRepository
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return $this->model->where('pid',0)->get();
    }

    /**
     * @param $orderreview
     * @return mixed
     * order_item表 根据评论获取产品
     */
    public function find_product($orderreview)
    {
        return DB::table('order_item')->where([
                    'order_id' => $orderreview->order_id,
                    'item_id' => $orderreview->goods_id,
                    'options' => $orderreview->goods_options
                ])->get()->first();
    }

    /**
     * @param $data
     * 产品回复品论 product_comments_reply
     */
    public function create($data)
    {
        try{
            DB::transaction(function()use ($data){
                //允许展示
                $this->approve_review( $data['review_id'] );

                $replies = ProductReviewReply::pluck('dialog_id');
                $fromUserId = user()->id;
                $toUserId = $data['to_user_id'];

                if( count($replies) == 0 ){
                    $dialog_id = $fromUserId.'-'.$toUserId;
                }else{
                    $dialog_id = findDialogId($replies,$fromUserId,$toUserId);
                    if( !$dialog_id ){
                        $dialog_id = $fromUserId.'-'.$toUserId;
                    }
                }

                //添加回复
                if( isset($data['review_reply']) ){
                    info( $data['review_reply'] );
                    $bool = DB::table('product_comments_reply')->insert([
                        'content' => $data['review_reply'],
                        'product_comment_id' => $data['review_id'],
                        'user_id' => user()->id,
                        'is_show' => 1,
                        'to_user_id' => $data['to_user_id'],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'dialog_id' => $dialog_id
                    ]);
                    //回复添加完 更新product_comments表的回复数量
                    $review = DB::table('product_comments')->where('id',$data['review_id'])->get()->first() ;
                    $org_count = $review->reply_count;
                    DB::table('product_comments')->where('id',$data['review_id'])->update([
                        'reply_count' => $org_count + 1
                    ]);

                    //$reply =  DB::table('product_comments_reply')->orderBy('created_at','desc')->get()->first();
                    $reply =  ProductReviewReply::orderBy('created_at','desc')->get()->first();
                    //通知用户
                    if( $data['to_user_id'] != user()->id ){
                        getUser( $data['to_user_id'] )->notify(new CommentReplyNotification( $reply ));
                    }
                }
            });
        }catch (Exception $e){
            return false;
        }
        return true;
    }

    /**
     * @param $reviewId
     */
    public function approve_review($reviewId)
    {
        DB::table('product_comments')->where('id',$reviewId)->update([
            'is_show' => 1
        ]);
    }
}
