<?php
/**
 * Created by PhpStorm.
 * User: yixin
 * Date: 2018/6/12
 * Time: 16:26
 */

namespace Modules\Sale\Http\Controllers;


use App\Http\Controllers\Controller;
use AjaxResponse;
use App\Services\ImageH;
use Illuminate\Http\Request;
use Modules\Mpay\Entities\Order;
use Modules\Sale\Entities\OrderReview;
use Modules\Sale\Repositories\OrderReviewRepository;

/**
 * Class ReviewController
 * @package Modules\Sale\Http\Controllers
 */
class ReviewController extends Controller
{
    protected $order_review;
    public function __construct(OrderReviewRepository $order_review)
    {
        $this->order_review = $order_review;
    }
    /**
     * @param $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function review_create($order)
    {
        $c_order = Order::where('order_id',$order)->get()->first();
        $products = $c_order->product;
        return view('usercenter.order-review',compact( 'order','products'));
    }

    /**
     * @param Request $request
     * @param $order
     * @return \Illuminate\Http\RedirectResponse|string
     * 用户收到货后 对商品进行评论 针对sku 用户最多有两次评论的机会 就是追评价
     */
    public function review_save(Request $request , $order)
    {
        $data = $request->all();
        $reviews = $data['review'] ;

        $files = $request->file("appraise_img_path");
        $c_order = Order::where('order_id',$order)->get()->first();
        try{
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

                $check = OrderReview::where(function($query)use($data,$order){
                    $query->where('user_id',user()->id)
                        ->where('order_id',$order)
                        ->where('goods_id','123')
                        ->where('goods_options',$data['goods_options'])
                    ;
                })->get();

                //用户有两次评论的权力 在收货后的 30天内
                if(  !!strtotime($c_order->consign_time)  &&  strtotime($c_order->consign_time) > 0  ){
                    $order_receive_date =  strtotime( $c_order->consign_time )  ;
                    $review_permit_date = date("Y-m-d h:i:s" , strtotime("+3 day" ,$order_receive_date )  );
                    if( count( $check->toArray() ) < 2 && strtotime('now') < strtotime($review_permit_date) ){
                        OrderReview::create($data);
                    }
                }
            }
        }catch (Exception $e){
            return $e->getMessage();
        }

        return redirect('/')->with('message','Thank you for your operation, your reviews are under processing');
    }

    public function review_reply_save(Request $request)
    {
        $data = $request->all();
        $bool = $this->order_review->create($data);
        return $bool ? AjaxResponse::success('') : AjaxResponse::fail('');
    }
}