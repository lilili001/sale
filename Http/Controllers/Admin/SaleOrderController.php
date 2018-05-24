<?php

namespace Modules\Sale\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mpay\Entities\Order;
use Modules\Mpay\Repositories\OrderRepository;
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
        $saleorders = $this->saleorder->all();
        return view('sale::admin.saleorders.index', compact('saleorders'));
    }

    public function confirm_payment($order)
    {
        $bool =$this->saleorder->confirm_payment($order);
        return $bool ? AjaxResponse::success('success') : AjaxResponse::fail('fail');
    }
    
    public function detail(Request $request,$order)
    {
        $order = Order::where('order_id',$order)->get()->first();
        return view('sale::admin.saleorders.detail',compact('order'));
    }

    public function ship($order)
    {
        $bool = $this->saleorder->ship($order,request('all'));
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
