<?php

namespace Modules\Sale\Repositories;

use Modules\Core\Repositories\BaseRepository;
use Modules\Mpay\Entities\Order;

interface SaleOrderRepository extends BaseRepository
{
    public function all();
    public function ship($order,$data );
    public function confirm_payment($order);
    public function updateOrderOperation($order,$status);
    public function cancel($order);
    public function order_with_supplier($order);

    public function confirm_order_receipt($order);

    public function refund_apply($order);

    public function refund_approve($order);

    public function refund_return_apply($order , $data);

    public function return_approve(  $order);
}
