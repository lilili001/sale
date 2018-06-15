<?php

namespace Modules\Sale\Repositories;

use Modules\Core\Repositories\BaseRepository;

interface OrderReviewRepository extends BaseRepository
{
    public function all();

    public function find_product($orderreview);

    public function approve_review($reviewId);
}
