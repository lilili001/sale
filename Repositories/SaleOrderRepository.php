<?php

namespace Modules\Sale\Repositories;

use Modules\Core\Repositories\BaseRepository;

interface SaleOrderRepository extends BaseRepository
{
    public function all();
    public function ship($order,$data );
    public function confirm_payment($order);
    public function updateOrderOperation($order,$status);
}
