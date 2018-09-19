<?php

namespace Modules\Sale\Repositories\Cache;

use Modules\Sale\Repositories\OrderReturnRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheOrderReturnDecorator extends BaseCacheDecorator implements OrderReturnRepository
{
    public function __construct(OrderReturnRepository $orderreturn)
    {
        parent::__construct();
        $this->entityName = 'sale.orderreturns';
        $this->repository = $orderreturn;
    }
}
