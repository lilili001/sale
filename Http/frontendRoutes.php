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

    $router->post('cancel/{order}',[
        'as' => 'frontend.order.cancel',
        'uses' => 'PublicController@cancel',
        'middleware' => 'logged.in'
    ]);

    //退款申请
    $router->post('refund_apply/{order}',[
        'as' => 'frontend.order.refund',
        'uses' => 'PublicController@refund',
        'middleware' => 'logged.in'
    ]);

    //退款退货申请
    $router->post('refund_return_apply/{order}',[
        'as' => 'frontend.order.refund_return_apply',
        'uses' => 'PublicController@refund_return_apply',
        'middleware' => 'logged.in'
    ]);
});
