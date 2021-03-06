<?php

namespace Modules\Sale\Repositories\Eloquent;

use App\Services\ImageH;
use Illuminate\Support\Facades\DB;
use Modules\Sale\Entities\Order;
use Modules\Sale\Entities\OrderDelivery;
use Modules\Sale\Entities\OrderOperation;
use Modules\Sale\Repositories\OrderRepository;
use Modules\Sale\Entities\Comment;
use Modules\Sale\Entities\OrderRefund;
use Modules\Sale\Entities\OrderReturn;
use Modules\Sale\Repositories\SaleOrderRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Carbon\Carbon;
use Modules\Sale\Repositories\TrackingRepository;
use Modules\Sale\Trackingmore;

/**
 * Class
 * @package Modules\Sale\Repositories\Eloquent
 */
class EloquentSaleOrderRepository extends EloquentBaseRepository implements SaleOrderRepository
{
    /**
     * EloquentSaleOrderRepository constructor.
     * @param TrackingRepository $tracking
     */

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
       return Order::orderBy('created_at', 'desc')->paginate(10);
    }
    public function all_frontend()
    {
        return Order::where('user_id',user()->id)->orderBy('created_at', 'desc')->paginate(10);
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
    public function ship($order, $data,$tracking)
    {
        try{
            DB::transaction(function() use( $order, $data , $tracking ) {
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

                //创建一个追踪项目
                $tracking->createTracking( $order, $data['shipping_method'], $data['tracking_number'] );
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
     * todo 用webhook
     */
    public function confirm_order_receipt($order,$updateTime){
        try{
            DB::transaction(function()use($order,$updateTime){
                Order::where('order_id',$order)
                    ->update([
                        'order_status' => 9, //订单完成
                        'consignee_time' =>$updateTime
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
    public function refund_apply($data){
        try{
            DB::transaction(function() use ($data){
                Order::where('order_id',$data['order'])
                    ->update([
                        'order_status' => 15, //退款申请
                    ]);

                //退款表插入一条申请数据
                $data1 = [
                    'refund_no' => build_no('RE'),
                    'order_id' => $data['order'],
                    'is_order_shipped' => 0,
                    'need_return_goods' => 0,
                    'amount' => $data['amount'],
                    'item_id' => $data['itemid'],//order item id,并非产品id
                    'currency'=> $data['currency'],
                    'created_at' => Carbon::now(),
                    'user_id' => user()->id
                ];
                DB::table('order_refund')->insert($data1);

                $this->updateOrderOperation($data['order'] , 15);
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
    public function refund_approve($order,$refundId)
    {
        info($order);
        try{
            DB::transaction(function()use ( $order ,$refundId ){

                DB::table('orders')->where('order_id',$order)
                    ->update([
                        'order_status' => 16 //退款申请 审批通过
                    ]);

                DB::table('order_refund')->where('refund_no',$refundId)->update([
                    'approve_status' => 1,
                    'updated_at' => Carbon::now()
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
    public function refund_return_apply($order , $data ){
        try{
            DB::transaction(function ()use($order , $data ){

                //退款金额 信息写入退款单
                $c_order =Order::where('order_id',$order)->get()->first() ;

                $data_arr = [
                    'order_id' => $order,
                    'amount'   => $data['refund_amount'],
                    'is_order_shipped' => $c_order->is_shipped,
                    'need_return_goods'=> $c_order->is_shipped != 0,
                    'user_id' => user()->id,
                    'created_at' => Carbon::now(),
                    'item_id' => $data['item_id'] //order_item的id
                ];

                DB::table('order_refund')->insert($data_arr);

                $refund_order =  OrderRefund::where('order_id',$order)->get()->first() ;

                //客户退货上传的图片保存 和 退款原因等留言 写入沟通的comment表
                $customer_files = '';
                if( isset( $data['orderfile'] ) && count( $data['orderfile'] )>0){
                    $filelist = (new ImageH())->upload($data['orderfile']);
                    $customer_files = implode(';', $filelist)  ;
                }

                //退货之前先要进行退款退货申请 买卖双方沟通都在refund_return
                DB::table('comments')->insert([
                    'user_id' => user()->id,
                    'body' => $data['refund_reason'],
                    'pid' => 0,
                    'img_url' => $customer_files,
                    'commentable_id' => $refund_order->id,
                    'commentable_type' => 'Modules\Sale\Entities\OrderRefund',
                    'created_at' => Carbon::now()
                ]);

                //订单状态修改
                DB::table('orders')->where('order_id',$order)->update([
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
     * @param $order
     * @param $data
     * @return bool
     * 退货审批
     */
    public function return_approve_operation($order , $data )
    {
        $status = $data['suggestion'] == 1 ? 11 : 21;
        try{
            DB::transaction(function ()use($order , $data,$status){
                //更新订单表
                DB::table('orders')->where('order_id',$order)->update([
                    'order_status' => $status
                ]);
                //更新退款表状态
                DB::table('order_refund')->where('order_id',$order)->update([
                    'approve_status' => 1
                ]);

                $refund_item = OrderRefund::where('order_id',$order)->get()->first();

                //更新comment表 通过或驳回 卖家留言 通过则留地址 不通过则留原因
                DB::table('comments')->insert([
                    'user_id' => user()->id,
                    'body' => $data['content'],
                    'pid' => 0,
                    'commentable_id' => $refund_item->id,
                    'commentable_type' => 'Modules\Sale\Entities\OrderRefund',
                    'created_at' => Carbon::now()
                ]);

                $this->updateOrderOperation($order , $status);
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

    /**
     * @param $order
     * 查询退货原因
     */
    public function get_return_reason($order)
    {
        $refund = OrderRefund::where('order_id',$order)->get()->first();
        return $refund->comments ;
    }

    /**
     * @param $order
     * 确认收货
     */
    public function confirm_receipt($order){
        try{
            DB::transaction(function()use($order){
                Order::where('order_id',$order)->update(['order_status' => 9 ,'consign_time' => Carbon::now() ]);//付款成功 //后续紧随状态修改为正在出库6
                $this->updateOrderOperation($order , 9);
            });

        }catch (Exception $e){
            info( 'confirm order receipt error:' .  $e->getMessage() );
            return false;
        }
        return true;
    }

    /**
     * @param $orderItem
     * 买家填写退货单
     */
    public function return_order($data,$tracking){

        try{
            unset( $data['_token'] );
            $data = [
               'order_id' => $data['orderid'],
               'goods_id' => $data['itemid'],
               'delivery' => $data['delivery'],
               'tracking_no' => $data['tracking_no'],
               'created_at' => Carbon::now(),
               'shipping_time' => Carbon::now(),
               'return_status' => 1
            ] ;
            DB::transaction(function() use($data) {
                DB::table('orders')->where('order_id',$data['order_id'])->update(['order_status' => 12]);
                $this->updateOrderOperation($data['order_id'] , 12);
                DB::table('order_return')->insert($data);
            });

            //创建一个追踪项目
            $tracking->createTracking( $data['orderid'], $data['delivery'], $data['tracking_no'] );

        }catch (Exception $e){
            info('return order error:',$e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * @param $data
     * "pending": 0,
    "notfound": 3,
    "transit": 5,
    "pickup": 0,
    "delivered": 25,
    "undelivered": 0,
    "exception": 0,
    "expired": 1
     */
    public function shipping_webhook($data )
    {
        //如果已签收则更改订单状态
        $webhook_data = $data['data'];
        if($webhook_data['status'] == 'pickup' ){
            $this->confirm_order_receipt($webhook_data['order_id'] ,$data['updated_at'] ) ;
        }

        $order = Order::where('order_id' , $webhook_data['order_id'])->get()->first();

        //更新发货表
        $order->delivery()->update([
            'status' => $data['status'],
            'updated_at' => $data['updated_at']
        ]);

        //退货物流 查询退货表是否有满足条件的 如果有则更新
        $orderid = $webhook_data['order_id'];
        $orderreturn = OrderReturn::where([
            'order_id' => $orderid,
            'tracking_no' => $webhook_data['tracking_number']
        ])->get();

        if( count( $orderreturn ) > 0 && $webhook_data['status'] == 'pickup' ){
            OrderReturn::where([
                'order_id' => $orderid,
                'tracking_no' => $webhook_data['tracking_number']
            ])->update([
                'update_time' =>$webhook_data['updated_at'] ,
                'pickup_time' => Carbon::now()
            ]);
        }

        info(now().'webhook' );

        //更新物流追踪信息写入数据库
        $shipping = $order->delivery()->get()->first();

        $tracking_data = [
            'order_id' => $webhook_data['order_id'],
            'tracking_number' => $webhook_data['tracking_number'],
            'carrier' => $webhook_data['carrier_code'],
            'status' => $webhook_data['status'],
            'original_country' => $webhook_data['original_country'],
            'destination_country' => $webhook_data['destination_country'],
            'origin_info' => json_encode( $webhook_data['origin_info'] ),
            'destination_info' => json_encode( $webhook_data['destination_info'] )
        ];
        $shipping->tracking()->create($tracking_data);
    }
}
