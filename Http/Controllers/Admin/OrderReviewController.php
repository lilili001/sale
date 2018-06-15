<?php

namespace Modules\Sale\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Sale\Entities\OrderReview;
use Modules\Sale\Http\Requests\CreateOrderReviewRequest;
use Modules\Sale\Http\Requests\UpdateOrderReviewRequest;
use Modules\Sale\Repositories\OrderReviewRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class OrderReviewController extends AdminBaseController
{
    /**
     * @var OrderReviewRepository
     */
    private $orderreview;

    public function __construct(OrderReviewRepository $orderreview)
    {
        parent::__construct();
        $this->orderreview = $orderreview;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $orderreviews = $this->orderreview->all();
        return view('sale::admin.orderreviews.index', compact('orderreviews'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('sale::admin.orderreviews.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateOrderReviewRequest $request
     * @return Response
     */
    public function store(CreateOrderReviewRequest $request)
    {

        $this->orderreview->create($request->all());

        return redirect()->route('admin.sale.orderreview.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('sale::orderreviews.title.orderreviews')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  OrderReview $orderreview
     * @return Response
     */
    public function edit(OrderReview $orderreview)
    {
        //获取当前评论的产品
        $product = $this->orderreview->find_product($orderreview);
        return view('sale::admin.orderreviews.edit', compact('orderreview','product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  OrderReview $orderreview
     * @param  UpdateOrderReviewRequest $request
     * @return Response
     */
    public function update(OrderReview $orderreview, UpdateOrderReviewRequest $request)
    {
        $this->orderreview->update($orderreview, $request->all());

        return redirect()->route('admin.sale.orderreview.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('sale::orderreviews.title.orderreviews')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  OrderReview $orderreview
     * @return Response
     */
    public function destroy(OrderReview $orderreview)
    {
        $this->orderreview->destroy($orderreview);

        return redirect()->route('admin.sale.orderreview.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('sale::orderreviews.title.orderreviews')]));
    }
}
