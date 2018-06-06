<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/order'], function (Router $router) {
    $router->get('list',[
        'as' => 'frontend.order.index',
        'uses' => 'PublicController@index',
        'middleware' => 'logged.in'
    ]);

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
    $router->post('refund-approve/{order}',[
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
        'uses' => 'PublicController@return_approve',
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
});
