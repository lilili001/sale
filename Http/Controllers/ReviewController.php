<?php
/**
 * Created by PhpStorm.
 * User: yixin
 * Date: 2018/6/12
 * Time: 16:26
 */

namespace Modules\Sale\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Services\ImageH;
use Illuminate\Http\Request;
use Modules\Mpay\Entities\Order;
use Modules\Sale\Entities\ProductComment;

class ReviewController extends Controller
{
    public function review_create($order)
    {
        $c_order = Order::where('order_id',$order)->get()->first();
        $products = $c_order->product;
        return view('usercenter.order-review',compact( 'order','products'));
    }

    public function review_save(Request $request , $order)
    {
        $data = $request->all();
        $reviews = $data['review'] ;

        $files = $request->file("appraise_img_path");
        $c_order = Order::where('order_id',$order)->get()->first();
        //上传图片
        foreach ( $reviews as $key=>$review ){
            $img_path = '';
            if( isset($files[$key]) ){
                $paths = (new ImageH())->upload($files[$key]);
                $img_path = implode(';',$paths) ;
            }
            $data = array_merge($reviews[$key],[
                'order_id' => $order,
                'user_id' => user()->id,
                'pid' => 0,
                'is_show' => 0,
                'appraise_img_path' => $img_path
            ]);

            $check = ProductComment::where(function($query)use($data,$order){
                $query->where('user_id',user()->id)
                    ->where('order_id',$order)
                    ->where('goods_id','123')
                    ->where('goods_options',$data['goods_options'])
                    ->where('comment_times','<',2);
            })->get();

            //用户有两次评论的权力 在收货后的 30天内
            if(  !!strtotime($c_order->consign_time)  &&  strtotime($c_order->consign_time) > 0  ){
                $order_receive_date =  strtotime( $c_order->consign_time )  ;
                $review_permit_date = date("Y-m-d h:i:s" , strtotime("+3 day" ,$order_receive_date )  );
                if( count( $check->toArray() ) < 2 && strtotime('now') < strtotime($review_permit_date) ){
                    ProductComment::create($data);
                }
            }
        }
    }

    public function review_delete()
    {
        
    }
}