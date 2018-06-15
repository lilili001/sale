<?php

namespace Modules\Sale\Repositories\Cache;

use Modules\Sale\Repositories\OrderReviewRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheOrderReviewDecorator extends BaseCacheDecorator implements OrderReviewRepository
{
    public function __construct(OrderReviewRepository $orderreview)
    {
        parent::__construct();
        $this->entityName = 'sale.orderreviews';
        $this->repository = $orderreview;
    }
}
