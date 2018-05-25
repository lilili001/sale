<?php

namespace Modules\Sale\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mpay\Entities\Order;
use Modules\Mpay\Repositories\OrderRepository;
use Modules\Sale\Entities\SaleOrder;
use Modules\Sale\Repositories\SaleOrderRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use AjaxResponse;

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

    /**
     * PublicController constructor.
     * @param SaleOrderRepository $saleorder
     * @param OrderRepository $order
     */
    public function __construct(SaleOrderRepository $saleorder , OrderRepository $order )
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
        $pageClass = 'order';
        return view('usercenter.order', compact('orders','pageClass'));
    }

    /**
     * @param $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail($order)
    {
        $order = Order::where('order_id',$order)->get()->first();
        $pageClass = 'order';
        return view('usercenter.order-detail',compact('order','pageClass'));
    }

    /**
     * @param $order
     * 取消申请 后台取消
     */
    public function cancel($order)
    {
        
    }

    /**
     * @param $order
     * 退款申请 后台人工点击审批通过 退款
     */
    public function refund_apply($order)
    {

    }

    /**
     * @param $order
     * 退款退货申请 后台人工点击审批通过 , 买家退货, 收到退后后变更状态   退款成功
     */
    public function refund_return_apply($order)
    {

    }
    
}
