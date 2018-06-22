<?php

namespace Modules\Sale\Repositories;

use Modules\Core\Repositories\BaseRepository;
use Modules\Mpay\Entities\Order;

interface SaleOrderRepository extends BaseRepository
{
    public function all();
    public function ship($order,$data ,$tracking );
    public function confirm_payment($order);
    public function updateOrderOperation($order,$status);
    public function cancel($order);
    public function order_with_supplier($order);

    public function confirm_order_receipt($order ,$updateTime );

    public function refund_apply($data);

    public function refund_approve($order,$refundId);

    public function refund_return_apply($order , $data);

    public function get_return_reason($order);

    public function return_approve_operation($order , $data );

    public function confirm_receipt($order);

    public function return_order($data,$tracking);
    public function shipping_webhook($data);
}
