<?php
/**
 * Created by PhpStorm.
 * User: yixin
 * Date: 2018/5/23
 * Time: 14:45
 */

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    protected $table = 'order_address';
    protected $guarded = [];
}