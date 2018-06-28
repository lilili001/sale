<?php

namespace Modules\Sale\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Sale\Entities\Order;
use Modules\Sale\Repositories\OrderRepository;
use Modules\Sale\Entities\OrderRefund;
use Modules\Sale\Entities\SaleOrder;
use Modules\Sale\Http\Requests\CreateSaleOrderRequest;
use Modules\Sale\Http\Requests\UpdateSaleOrderRequest;
use Modules\Sale\Repositories\SaleOrderRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use AjaxResponse;
use Modules\Sale\Repositories\TrackingRepository;
use Modules\Sale\Trackingmore;

class SaleOrderController extends AdminBaseController
{
    /**
     * @var SaleOrderRepository
     */
    private $saleorder;
    private $order;
    private $tracking;
    public function __construct(SaleOrderRepository $saleorder ,OrderRepository $order , TrackingRepository $tracking )
    {
        parent::__construct();

        $this->saleorder = $saleorder;
        $this->tracking = $tracking;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $orders = $this->saleorder->all();
        return view('sale::admin.saleorders.index', compact('orders'));
    }

    public function confirm_payment($order)
    {
        $bool =$this->saleorder->confirm_payment($order);
        return $bool ? AjaxResponse::success('success') : AjaxResponse::fail('fail');
    }
    
    public function detail(Request $request,$orderId)
    {
        $order = Order::where('order_id',$orderId)->get()->first();
        //退款信息查询

        $order_refund = OrderRefund::where('order_id' ,$orderId )->get()->first();

        $refund_comments = $order_refund->comments->toArray();

        $shipping = $order->delivery()->get()->first();

        $tracking =  $this->tracking->getSingleTrackingResult( $shipping->delivery , $shipping->tracking_number , 'en' )  ;

        return view('sale::admin.saleorders.detail',compact('order' , 'refund_comments', 'tracking'));
    }

    public function ship(Request $request,$order)
    {
        $bool = $this->saleorder->ship($order,$request->all() ,$this->tracking );
        return $bool ? AjaxResponse::success('success') : AjaxResponse::fail('fail');
    }

    public function refund($orderId)
    {
        //如果是paypal
        $data = request('all');
        $this->order->refund($orderId,$data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  SaleOrder $saleorder
     * @return Response
     */
    public function destroy(SaleOrder $saleorder)
    {
        $this->saleorder->destroy($saleorder);

        return redirect()->route('admin.sale.saleorder.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('sale::saleorders.title.saleorders')]));
    }

    protected function verify($timeStr,$useremail,$signature){
        $hash="sha256";
        $result=hash_hmac($hash,$timeStr,$useremail);
        return strcmp($result,$signature)==0?1:0;
    }

    public function webhook_shipping()
    {
        $data = file_get_contents("php://input");
        //验证是消息来源
        $verify = $this->verify( $data['verifyInfo']['timeStr'] , '2861166132@qq.com' ,$data['verifyInfo']['signature']  );
        //如果已签收则修改订单状态
        if($verify){
            $this->saleorder->shipping_webhook($data , $this->tracking);
        }
    }
}
