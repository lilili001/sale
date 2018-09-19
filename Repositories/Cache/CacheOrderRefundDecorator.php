<?php

namespace Modules\Sale\Repositories\Cache;

use Modules\Sale\Repositories\OrderRefundRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheOrderRefundDecorator extends BaseCacheDecorator implements OrderRefundRepository
{
    public function __construct(OrderRefundRepository $orderrefund)
    {
        parent::__construct();
        $this->entityName = 'sale.orderrefunds';
        $this->repository = $orderrefund;
    }
}
