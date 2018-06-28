<?php
/**
 * Created by PhpStorm.
 * User: yixin
 * Date: 2018/5/10
 * Time: 14:51
 */

namespace Modules\Sale\Repositories;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Sale\Entities\Order;
use Modules\Sale\Entities\PayPalIPN;


class IPNRepository
{
    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param $event
     * @param $verified
     * @param $order_id
     */
    public function handle($event, $verified, $order_id)
    {
        info('ipn');
        $object = $event->getMessage();

        if (is_numeric($order_id)) {
            $order = Order::where([ 'order_id' =>  ($order_id)] )->get()->first() ;
        }

        if (empty($order)) {
            $order = Order::findByTransactionId(
                $object->get('txn_id')
            )->first();
        }

        $paypal = PayPalIPN::create([
            'verified' => $verified,
            'transaction_id' => $object->get('txn_id'),
            'order_id' => $order ? $order->order_id : null,
            'payment_status' => $object->get('payment_status'),
            'request_method' => $this->request->method(),
            'request_url' => $this->request->url(),
            //'request_headers' => json_encode($this->request->header()),
            'payload' => json_encode($this->request->all()),
        ]);

        if ($paypal->isVerified() && $paypal->isCompleted()) {

            if ($order && $order->unpaid()) {
                $order->update([
                    'payment_status' => $order::COMPLETED,
                    'order_status' => 3,
                    'is_paid' => 1,
                    'payment_time' => Carbon::now()
                ]);

                // notify customer
                // notify order handling staff
                // update database logic
            }
        }else{
            info('fail');
        }
    }
}