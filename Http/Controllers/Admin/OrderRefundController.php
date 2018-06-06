<?php

namespace Modules\Sale\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Sale\Entities\OrderRefund;
use Modules\Sale\Http\Requests\CreateOrderRefundRequest;
use Modules\Sale\Http\Requests\UpdateOrderRefundRequest;
use Modules\Sale\Repositories\OrderRefundRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class OrderRefundController extends AdminBaseController
{
    /**
     * @var OrderRefundRepository
     */
    private $orderrefund;

    public function __construct(OrderRefundRepository $orderrefund)
    {
        parent::__construct();

        $this->orderrefund = $orderrefund;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$orderrefunds = $this->orderrefund->all();

        return view('sale::admin.orderrefunds.index', compact(''));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('sale::admin.orderrefunds.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateOrderRefundRequest $request
     * @return Response
     */
    public function store(CreateOrderRefundRequest $request)
    {
        $this->orderrefund->create($request->all());

        return redirect()->route('admin.sale.orderrefund.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('sale::orderrefunds.title.orderrefunds')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  OrderRefund $orderrefund
     * @return Response
     */
    public function edit(OrderRefund $orderrefund)
    {
        return view('sale::admin.orderrefunds.edit', compact('orderrefund'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  OrderRefund $orderrefund
     * @param  UpdateOrderRefundRequest $request
     * @return Response
     */
    public function update(OrderRefund $orderrefund, UpdateOrderRefundRequest $request)
    {
        $this->orderrefund->update($orderrefund, $request->all());

        return redirect()->route('admin.sale.orderrefund.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('sale::orderrefunds.title.orderrefunds')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  OrderRefund $orderrefund
     * @return Response
     */
    public function destroy(OrderRefund $orderrefund)
    {
        $this->orderrefund->destroy($orderrefund);

        return redirect()->route('admin.sale.orderrefund.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('sale::orderrefunds.title.orderrefunds')]));
    }
}
