<?php

namespace Modules\Sale\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mpay\Entities\Order;
use Modules\Mpay\Repositories\OrderRepository;
use Modules\Sale\Entities\OrderRefund;
use Modules\Sale\Entities\SaleOrder;
use Modules\Sale\Http\Requests\CreateSaleOrderRequest;
use Modules\Sale\Http\Requests\UpdateSaleOrderRequest;
use Modules\Sale\Repositories\SaleOrderRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use AjaxResponse;

class SaleOrderController extends AdminBaseController
{
    /**
     * @var SaleOrderRepository
     */
    private $saleorder;
    private $order;

    public function __construct(SaleOrderRepository $saleorder ,OrderRepository $order )
    {
        parent::__construct();

        $this->saleorder = $saleorder;
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
        dd( $order_refund );
        //$refund_comments = $order_refund->comments->toArray();

        return view('sale::admin.saleorders.detail',compact('order' , 'refund_comments'));
    }

    public function ship(Request $request,$order)
    {
        $bool = $this->saleorder->ship($order,$request->all());
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
}
