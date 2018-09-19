<?php

namespace Modules\Sale\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Sale\Entities\Order;
use Modules\Sale\Entities\OrderOperation;
use Modules\Sale\Repositories\OrderRepository;
use Modules\Sale\Entities\OrderRefund;
use Modules\Sale\Entities\OrderReturn;
use Modules\Sale\Entities\SaleOrder;
use Modules\Sale\Repositories\SaleOrderRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use AjaxResponse;
use Modules\Sale\Repositories\TrackingRepository;
use Modules\Sale\Trackingmore;
use Modules\User\Entities\Sentinel\User;

/**
 * Class PublicController
 * @package Modules\Sale\Http\Controllers
 */
class PublicController extends AdminBaseController
{
    /**
     * @var SaleOrderRepository
     */
    private $saleorder;
    /**
     * @var
     */
    private $order;
    private $tracking;

    /**
     * PublicController constructor.
     * @param SaleOrderRepository $saleorder
     * @param OrderRepository $order
     */
    public function __construct(SaleOrderRepository $saleorder , OrderRepository $order , TrackingRepository $tracking )
    {
        parent::__construct();
        $this->saleorder = $saleorder;
        $this->order = $order;
        $this->tracking = $tracking;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
<<<<<<< HEAD
        $orders = $this->saleorder->all_frontend();
=======
        $orders = $this->saleorder->all();
>>>>>>> 7dbf57ae1ecb6db065d795619c74a38cb55d4132
        $pageClass = 'order';
        return view('usercenter.order', compact('orders','pageClass'));
    }

    /**
     * @param $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail(Request $request,$orderId)
    {
        $order = Order::where('order_id',$orderId)->get()->first();
        $pageClass = 'order';

        $order_refund = OrderRefund::where('order_id' ,$orderId )->get()->first();
        if( !empty($order_refund)  ){
            $refund_comments = $order_refund->comments->toArray();
        }else{
            $refund_comments = null;
        }

        if( $request->expectsJson() ){
            return Ajaxresponse::success('',compact('order' ));
        }
        //发货物流追踪信息 查询接口
        $shipping = $order->delivery()->get()->first();
        if ( isset($shipping) ){
            $tracking =  $this->tracking->getSingleTrackingResult( $shipping->delivery , $shipping->tracking_number , 'en' )  ;
        }
        return view('usercenter.order-detail',compact('order', 'refund_comments','pageClass' ,'tracking'));
    }

    /**
     * @param $order
     * 取消申请 后台取消
     */
    public function cancel($order)
    {
       $bool = $this->saleorder->cancel($order);
       return $bool? AjaxResponse::success('','取消成功') : AjaxResponse::fail('','取消失败') ;
    }

    /**
     * @param $order
     * @return mixed
     */
    public function order_with_supplier($order)
    {
        $bool = $this->saleorder->order_with_supplier($order);
       return $bool ? AjaxResponse::success('成功') :  AjaxResponse::fail('失败');
    }

    /**
     * @param $order
     * @return mixed
     */
    public function confirm_order_receipt($order)
    {
        $bool = $this->saleorder->confirm_order_receipt($order);
        return $bool ? AjaxResponse::success('成功') :  AjaxResponse::fail('失败');
    }
    
    /**
     * @param $order
     * 退款申请 后台人工点击审批通过 退款
     */
    public function refund_apply( Request $request, $order )
    {
        $data = array_merge( ['order'=>$order] , $request->all() );
        try{
            $bool = $this->saleorder->refund_apply( $data );
        }catch (Exception $e){
            return AjaxResponse::fail('退款申请失败');
        }
        return  AjaxResponse::success('退款申请成功');
    }

    /**
     * @param Order $order
     * @return mixed
     */
    public function refund_approve( $refundId)
    {
        $orderRefund =OrderRefund::where('refund_no',$refundId)->get()->first();

        $order = Order::where('order_id',$orderRefund->order_id)->get()->first();

        try{
           $bool = $this->saleorder->refund_approve($order->order_id,$refundId);
           //如果审批通过则立即退款
           if($bool){
               $this->order->refund( $order->transaction_id, [
                   'currency' => $orderRefund->currency,
                   'amount'   => $orderRefund->amount
               ]);
           }
        }catch (Exception $e){
            return AjaxResponse::fail('失败');
        }
        return  AjaxResponse::success('成功');
    }

    /**
     * @param $order
     * 退款退货申请 后台人工点击审批通过 , 买家退货, 收到退后后变更状态   退款成功
     */
    public function return_apply(Request $request, $order)
    {
       $bool = $this->saleorder->refund_return_apply($order ,$request->all() );
       if($request->expectsJson()  || $request->ajax() ){
           return $bool ? AjaxResponse::success('成功') : AjaxResponse::fail('失败');
       }
       return $bool;
    }

    /**
     * @param Request $request
     * @param $order
     * @return mixed
     */
    public function return_approve_operation(Request $request , $order)
    {
        $data = $request->all();
        $bool = $this->saleorder->return_approve_operation($order,$data);
        return $bool ? AjaxResponse::success('成功') : AjaxResponse::fail('失败');
    }

    /**
     * @param $order
     * @return mixed
     * 退货原因 从comment表获取买家留言和图片
     */
    public function refund_return_reason($order)
    {
        $comment = $this->saleorder->get_return_reason($order);
        $applier = User::find( $comment->first()->user_id );
        return $comment ? AjaxResponse::success('成功', compact('comment','applier')) : AjaxResponse::fail('失败');
    }

    public function confirm_receipt($order)
    {
        try{
            $bool = $this->saleorder->confirm_receipt($order);

        }catch (Exception $e){
            return $e->getMessage();
        }

        return redirect()
            ->route('frontend.order.review_create',['order'=>$order])
            ->with('order_complete','The goods are received, please make reviews for the goods');
    }

    public function return_order( Request $request)
    {
        $data = $request->all();
        $bool = $this->saleorder->return_order($data ,$this->tracking );
        return $bool ? AjaxResponse::success('') : AjaxResponse::fail('');
    }
}
