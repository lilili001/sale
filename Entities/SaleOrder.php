<?php

namespace Modules\Sale\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class SaleOrder extends Model
{
    use Translatable;

    protected $table = 'sale__saleorders';
    public $translatedAttributes = [];
    protected $fillable = [];
}
