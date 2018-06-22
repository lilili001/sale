<?php

namespace Modules\Sale\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Sale\Entities\OrderReturn;
use Modules\Sale\Http\Requests\CreateOrderReturnRequest;
use Modules\Sale\Http\Requests\UpdateOrderReturnRequest;
use Modules\Sale\Repositories\OrderReturnRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class OrderReturnController extends AdminBaseController
{
    /**
     * @var OrderReturnRepository
     */
    private $orderreturn;

    public function __construct(OrderReturnRepository $orderreturn)
    {
        parent::__construct();

        $this->orderreturn = $orderreturn;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $orderreturns = $this->orderreturn->all();
        return view('sale::admin.orderreturns.index', compact('orderreturns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('sale::admin.orderreturns.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateOrderReturnRequest $request
     * @return Response
     */
    public function store(CreateOrderReturnRequest $request)
    {
        $this->orderreturn->create($request->all());

        return redirect()->route('admin.sale.orderreturn.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('sale::orderreturns.title.orderreturns')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  OrderReturn $orderreturn
     * @return Response
     */
    public function edit(OrderReturn $orderreturn)
    {
        return view('sale::admin.orderreturns.edit', compact('orderreturn'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  OrderReturn $orderreturn
     * @param  UpdateOrderReturnRequest $request
     * @return Response
     */
    public function update(OrderReturn $orderreturn, UpdateOrderReturnRequest $request)
    {
        $this->orderreturn->update($orderreturn, $request->all());

        return redirect()->route('admin.sale.orderreturn.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('sale::orderreturns.title.orderreturns')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  OrderReturn $orderreturn
     * @return Response
     */
    public function destroy(OrderReturn $orderreturn)
    {
        $this->orderreturn->destroy($orderreturn);

        return redirect()->route('admin.sale.orderreturn.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('sale::orderreturns.title.orderreturns')]));
    }
}
