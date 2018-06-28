<?php
/**
 * Created by PhpStorm.
 * User: yixin
 * Date: 2018/5/9
 * Time: 16:49
 */

namespace Modules\Sale;
use Omnipay\Omnipay;

class PayPal
{
    /*
* @return mixed
*/
    public function gateway()
    {
        $gateway = Omnipay::create('PayPal_Express');

        $gateway->setUsername(config('paypal.credentials.username'));
        $gateway->setPassword(config('paypal.credentials.password'));
        $gateway->setSignature(config('paypal.credentials.signature'));
        $gateway->setTestMode(config('paypal.credentials.sandbox'));

        return $gateway;
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function purchase(array $parameters)
    {
        $response = $this->gateway()
            ->purchase($parameters)
            ->send();
        return $response;
    }

    /**
     * @param array $parameters
     */
    public function complete(array $parameters)
    {
        $response = $this->gateway()
            ->completePurchase($parameters)
            ->send();

        return $response;
    }

    public function search($parameters)
    {
        $response = $this->gateway()
            ->void();

        return $response;
    }

    public function refund($parameters)
    {
        return $this->gateway()
            ->refund($parameters)
            ;
    }
    /**
     * @param $amount
     */
    public function formatAmount($amount)
    {
        return number_format($amount, 2, '.', '');
    }

    /**
     * @param $order
     */
    public function getCancelUrl($order)
    {
        return route('paypal.checkout.cancelled', $order->order_id);
    }

    /**
     * @param $order
     */
    public function getReturnUrl($order)
    {
        return route('paypal.checkout.completed', $order->order_id);
    }

    /**
     * @param $order
     */
    public function getNotifyUrl($order)
    {
        $env = config('paypal.credentials.sandbox') ? "sandbox" : "live";

        return route('webhook.paypal.ipn', [$order->id, $env]);
    }
}