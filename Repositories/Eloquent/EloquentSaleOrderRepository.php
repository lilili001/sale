<?php

namespace Modules\Sale\Repositories\Eloquent;

use Modules\Mpay\Entities\Order;
use Modules\Mpay\Entities\OrderDelivery;
use Modules\Mpay\Entities\OrderOperation;
use Modules\Sale\Repositories\SaleOrderRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

/**
 * Class EloquentSaleOrderRepository
 * @package Modules\Sale\Repositories\Eloquent
 */
class EloquentSaleOrderRepository extends EloquentBaseRepository implements SaleOrderRepository
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
       return Order::all();
    }

    /**
     * @param $order
     * @param array $data
     * @return bool
     */
    public function ship($order, $data=[])
    {
        try{
            Order::where('order_id',$order)
                ->update([
                    'is_paid' => 1,
                    'is_shipped' => 1,
                    'order_status' => 7//已出库
                ]);

            //插入一条数据到发货表
            OrderDelivery::create([
                'order_id' => $order,
                'delivery' => $data['shipping_method'],
                'tracking_number' => $data['tracking_number'],
                'invoice_number' => build_no()
            ]);

            $this->updateOrderOperation($order , 7);

        }catch (Exception $e){
            info( 'shipping order error:' .  $e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * @param $order
     * @return bool
     */
    public function confirm_payment($order)
    {
        try{
            Order::where('order_id',$order)->update(['order_status' => 3]);//付款成功 //后续紧随状态修改为正在出库6

            $this->updateOrderOperation($order , 3);
        }catch (Exception $e){
            info( 'confirm order payment error:' .  $e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * @param $order
     * @param $status
     * @return mixed
     */
    public function updateOrderOperation($order, $status){
       return OrderOperation::create([
            'order_id' => $order,
            'order_status' => $status,
            'order_status_label' => config('order')[$status],
        ]);
    }

}
