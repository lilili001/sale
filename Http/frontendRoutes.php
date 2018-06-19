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
});
