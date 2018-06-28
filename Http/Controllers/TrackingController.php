<?php

namespace Modules\Sale\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Sale\Entities\Order;
use Modules\Sale\Entities\OrderOperation;
use Modules\Sale\Repositories\OrderRepository;
use Modules\Sale\Entities\OrderRefund;
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
class TrackingController extends AdminBaseController
{
    protected $tracking;
    public function __construct(TrackingRepository $tracking)
    {
        $this->tracking = $tracking;
    }

    public function getSingleTrackingResult(Request $request)
    {
        $carrier_code = $request->get('carrier_code');
        $tracking_number = $request->get('tracking_number');
        try{
           $data = $this->tracking->getSingleTrackingResult( $carrier_code, $tracking_number );
        }catch (Exception $e){
            return AjaxResponse::false('',$e->getMessage());
        }
        return AjaxResponse::success('' , $data );
    }
}
