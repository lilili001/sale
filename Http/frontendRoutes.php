<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/order'], function (Router $router) {
    //前台订单列表
    $router->get('list',[
        'as' => 'frontend.order.index',
        'uses' => 'PublicController@index',
        'middleware' => 'logged.in'
    ]);
    //订单详情
    $router->get('detail/{order}',[
        'as' => 'frontend.order.detail',
        'uses' => 'PublicController@detail',
        'middleware' => 'logged.in'
    ]);

    //取消订单
    $router->post('cancel/{order}',[
        'as' => 'frontend.order.cancel',
        'uses' => 'PublicController@cancel',
        'middleware' => 'logged.in'
    ]);

    //申请退款
    $router->post('apply-refund/{order}',[
        'as' => 'frontend.order.apply.refund',
        'uses' => 'PublicController@refund_apply',
        'middleware' => 'logged.in'
    ]);

    //退款审批
    $router->post('refund-approve/{refundId}',[
        'as' => 'frontend.order.refund.approve',
        'uses' => 'PublicController@refund_approve',
        'middleware' => 'logged.in'
    ]);

    //退货申请
    $router->post('refund_return_apply/{order}',[
        'as' => 'frontend.order.return_apply',
        'uses' => 'PublicController@return_apply',
        'middleware' => 'logged.in'
    ]);

    //退货审批通过
    $router->post('refund_return_approve/{order}',[
        'as' => 'frontend.order.return.approve',
        'uses' => 'PublicController@return_approve_operation',
        'middleware' => 'logged.in'
    ]);

    //根据订单号查询退货申请人 退货原因
    $router->post('refund_return_reason/{order}',[
        'as' => 'frontend.order.return.approve.reason',
        'uses' => 'PublicController@refund_return_reason',
        'middleware' => 'logged.in'
    ]);

    //是否和供应商订货标记
    $router->post('order_with_supplier/{order}',[
        'as' => 'frontend.order.order_with_supplier',
        'uses' => 'PublicController@order_with_supplier',
        'middleware' => 'logged.in'
    ]);
    //删除订单 订单完成货订单取消的时候可以删除订单
    $router->post('delete/{order}',[
        'as' => 'frontend.order.delete',
        'uses' => 'PublicController@delete',
        'middleware' => 'logged.in'
    ]);

    //前台确认收货
    $router->get('receive/{order}',[
        'as' => 'frontend.order.confirm_receipt',
        'uses' => 'PublicController@confirm_receipt',
        'middleware' => 'logged.in'
    ]);

    //前台填写退货单
    $router->post('return/orderitem',[
        'as' => 'frontend.order.return_order',
        'uses' => 'PublicController@return_order',
        'middleware' => 'logged.in'
    ]);

    //评论页
    $router->get('review_create/{order}',[
        'as' => 'frontend.order.review_create',
        'uses' => 'ReviewController@review_create',
        'middleware' => 'logged.in'
    ]);

    //产品评论提交
    $router->post('review_save/{order}',[
        'as' => 'frontend.order.review_save',
        'uses' => 'ReviewController@review_save',
        'middleware' => 'logged.in'
    ]);

    //产品评论回复提交
    $router->post('review_reply_save',[
        'as' => 'frontend.order.review_reply_save',
        'uses' => 'ReviewController@review_reply_save',
        'middleware' => 'logged.in'
    ]);

    //产品评论赞
    $router->post('product_comment_vote',[
        'as' => 'frontend.order.product_comment_vote',
        'uses' => 'ReviewController@product_comment_vote',
        'middleware' => 'logged.in'
    ]);

    //trackingmore test
    $router->get('trackingmore',[
        'as' => 'frontend.order.tracking',
        'uses' => 'TrackingController@index',
        'middleware' => 'logged.in'
    ]);

    $router->post('getSingleTrackingResult',[
        'as' => 'frontend.order.getSingleTrackingResult',
        'uses' => 'TrackingController@getSingleTrackingResult',
        'middleware' => 'logged.in'
    ]);

    //Order routes
    $router->post('save', [
        'uses' => 'OrderController@save',
        'as' => 'order.create'
    ]);
    $router->post('update', [
        'uses' => 'OrderController@update'
    ]);
    $router->get('delete',[
        'uses' => 'OrderController@update'
    ]);
});

//alipay routes
$router->group(['prefix' =>'/alipay'], function (Router $router) {
 
    $router->get('/checkout/{order}',[
        'uses' => 'AlipayController@checkout',
        'as' => 'alipay.checkout'
    ]);
    $router->get('return', [
        'uses' => 'AlipayController@return',
        'as' => 'alipay.return'
    ]);
    $router->post('notify', [
        'uses' => 'AlipayController@notify',
        'as' => 'alipay.notify'
    ]);
});
//paypal routes
Route::get('/orderdetail/{order?}', [
    'name' => 'PayPal Express Checkout',
    'as' => 'app.home',
    'uses' => 'PaypalController@form',
]);
Route::get('/paypal/checkout/{order}', [
    'name' => 'PayPal Express Checkout',
    'as' => 'checkout.payment.paypal',
    'uses' => 'PaypalController@checkout',
]);
Route::get('/paypal/{order}/completed', [
    'name' => 'PayPal Express Checkout',
    'as' => 'paypal.checkout.completed',
    'uses' => 'PaypalController@completed',
]);
Route::get('/paypal/{order}/cancelled', [
    'name' => 'PayPal Express Checkout',
    'as' => 'paypal.checkout.cancelled',
    'uses' => 'PaypalController@cancelled',
]);
Route::post('/paypal/webhook/{order?}/{env?}', [
    'name' => 'PayPal Express IPN',
    'as' => 'webhook.paypal.ipn',
    'uses' => 'PaypalController@webhook',
]);
//查询 paypal transaction
Route::get('/paypal/sale_detail/{transactionId}', [
    'name' => 'PayPal Express sale_detail',
    'as' => 'paypal.sale_detail',
    'uses' => 'PaypalController@sale_detail',
]);
//退款 refund
Route::get('/paypal/refund/{transactionId}',[
    'name' => 'PayPal Express refund',
    'as' => 'paypal.refund',
    'uses' => 'PaypalController@refund'
]);

Route::get('/paypal-checkout/error',[
    'name' => 'paypal.checkout.error',
    'as' => 'paypal.checkout.error',
    'uses' => 'PaypalController@checkoutError'
]);
/*
Route::get('/paypal-checkout/error',function(){
    return 'checkout error';
});*/

/*获取用户优惠券列表*/
Route::get('/promocode',[
    'name' => 'promocode',
    'as' => 'promocode',
    'uses' => 'PromocodeController@promo',
    'middleware' => 'logged.in'
]);

/*领券并使用*/
Route::post('/promocode/{code}',[
    'name' => 'get_promocode',
    'as' => 'get_promocode',
    'uses' => 'PromocodeController@getCouponCode',
    'middleware' => 'logged.in'
]);

/*检查优惠券是否有效*/
Route::post('/checkCuponCode/{code}',[
    'name' => 'checkCuponCode',
    'as' => 'checkCuponCode',
    'uses' => 'PromocodeController@checkCuponCode',
    'middleware' => 'logged.in'
]);

/*删除优惠券*/
Route::post('/disableCuponCode/{code}',[
    'name' => 'disableCuponCode',
    'as' => 'disableCuponCode',
    'uses' => 'PromocodeController@disableCuponCode',
    'middleware' => 'logged.in'
]);