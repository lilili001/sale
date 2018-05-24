<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;

class SaleOrderTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'sale__saleorder_translations';
}
