<?php

namespace Modules\Sale\Repositories\Cache;

use Modules\Sale\Repositories\CommentRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheCommentDecorator extends BaseCacheDecorator implements CommentRepository
{
    public function __construct(CommentRepository $comment)
    {
        parent::__construct();
        $this->entityName = 'sale.comments';
        $this->repository = $comment;
    }
}
