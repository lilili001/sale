<?php

namespace Modules\Sale\Repositories\Cache;

use Modules\Sale\Repositories\SaleOrderRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheSaleOrderDecorator extends BaseCacheDecorator implements SaleOrderRepository
{
    public function __construct(SaleOrderRepository $saleorder)
    {
        parent::__construct();
        $this->entityName = 'sale.saleorders';
        $this->repository = $saleorder;
    }
}
