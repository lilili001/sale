<?php

namespace Modules\Sale\Repositories\Eloquent;

use Illuminate\Support\Facades\DB;
use Modules\Mpay\Entities\Order;
use Modules\Mpay\Entities\OrderDelivery;
use Modules\Mpay\Entities\OrderOperation;
use Modules\Mpay\Repositories\OrderRepository;
use Modules\Sale\Repositories\SaleOrderRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Carbon;
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
     * @return bool
     */
    public function cancel($order)
    {
        try{
            Order::where('order_id',$order)
                ->update([
                    'order_status' => 5
                ]);
            $this->updateOrderOperation($order , 5);

        }catch (Exception $e){
            info('订单取消失败'. $e->getCode() . $e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * @param $order
     * @return bool
     */
    public function order_with_supplier($order)
    {
        try{
            DB::transaction(function () use ( $order ) {
                //更新订单表 状态变更为出库 [已和供应商订货]
                Order::where('order_id',$order)->update([
                    'is_ordered_with_supplier' => 1,
                    'order_with_supplier_at' => now(),
                    'order_status' => 6 //正在出库
                ]);
                //更新操作表 [状态更新]
                $this->updateOrderOperation($order , 6);

            });
        }catch (Exception $e){
            info('出库失败' . $e->getMessage());
                return false;
        }
        return true;
    }
    /**
     * @param $order
     * @param array $data
     * @return bool
     * 点击发货
     */
    public function ship($order, $data)
    {
        try{
            DB::transaction(function() use( $order, $data ) {
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
            });
        }catch (Exception $e){
            info( 'shipping order error:' .  $e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * @param $order
     * @return bool
     * 买家：确认收货 或者 物流签收后 自动更行
     * todo 定时任务
     */
    public function confirm_order_receipt($order){
        try{
            DB::transaction(function()use($order){
                Order::where('order_id',$order)
                    ->update([
                        'order_status' => 9 //订单完成
                    ]);

                $this->updateOrderOperation($order , 9);
            });
        }catch (Exception $e){
            info( 'shipping order error:' .  $e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * @param $order
     * @return bool
     * 买家：退款申请
     */
    public function refund_apply($order){

        try{
            DB::transaction(function() use ($order){
                Order::where('order_id',$order)
                    ->update([
                        'order_status' => 15 //退款申请
                    ]);

                $this->updateOrderOperation($order , 15);
            });
        }catch (Exception $e){
            info( 'apply refund error:' .  $e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * @param $order
     * @return bool
     * 卖家：退款申请审批通过
     */
    public function refund_approve($order)
    {
        try{
            DB::transaction(function()use ( $order ){

                Order::where('order_id',$order)
                    ->update([
                        'order_status' => 16 //退款申请 审批通过
                    ]);

                $this->updateOrderOperation($order , 16);
            });
        }catch (Exception $e){
            info( 'arrove refund error:' .  $e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * @param $order
     * 客户收到货后 退货退款申请
     */
    public function refund_return_apply($order){
        try{
            DB::transaction(function ()use($order){
                Order::where('order_id',$order)->update([
                    'order_status' => 10
                ]);

                $this->updateOrderOperation($order , 10);
            });
        }catch (Exception $e){
            info('return and refund failed' .  $e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * @param Order $order
     * @return bool
     * 用户收到货后 退款退货审批通过 买家需要在前台填写退货物流 前台有个物流的页面
     */
    public function return_approve(  $order)
    {
        try{
            DB::transaction(function ()use($order){
                Order::where('order_id',$order)->update([
                    'order_status' => 11
                ]);
                $this->updateOrderOperation($order , 11);
            });
        }catch (Exception $e){
            info('refund_return_approve failed' .  $e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * @param $order
     * @return bool
     * 确认付款
     */
    public function confirm_payment($order)
    {
        try{
            DB::transaction(function()use($order){
                Order::where('order_id',$order)->update(['order_status' => 3]);//付款成功 //后续紧随状态修改为正在出库6
                $this->updateOrderOperation($order , 3);
            });
        }catch (Exception $e){
            info( 'confirm order payment error:' .  $e->getMessage() );
            return false;
        }
        return true;
    }

    /**
     * @param $order
     * @param $status
     * @return mixed
     * 更新订单流程状态
     */
    public function updateOrderOperation($order, $status){
       return OrderOperation::create([
            'order_id' => $order,
            'order_status' => $status,
            'order_status_label' => config('order.status')[$status],
        ]);
    }
}
