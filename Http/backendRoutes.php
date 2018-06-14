<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/sale'], function (Router $router) {
    $router->bind('saleorder', function ($id) {
        return app('Modules\Sale\Repositories\SaleOrderRepository')->find($id);
    });
    $router->get('saleorders', [
        'as' => 'admin.sale.saleorder.index',
        'uses' => 'SaleOrderController@index',
        'middleware' => 'can:sale.saleorders.index'
    ]);

    $router->get('saleorders/{order}', [
        'as' => 'admin.sale.saleorder.detail',
        'uses' => 'SaleOrderController@detail',
        'middleware' => 'can:sale.saleorders.index'
    ]);

    $router->post('saleorders/ship/{order}', [
        'as' => 'admin.sale.saleorder.ship',
        'uses' => 'SaleOrderController@ship',
        'middleware' => 'can:sale.saleorders.index'
    ]);

    $router->post('saleorders/refund/{order}', [
        'as' => 'admin.sale.saleorder.refund',
        'uses' => 'SaleOrderController@refund',
        'middleware' => 'can:sale.saleorders.index'
    ]);

    $router->delete('saleorders/{saleorder}', [
        'as' => 'admin.sale.saleorder.destroy',
        'uses' => 'SaleOrderController@destroy',
        'middleware' => 'can:sale.saleorders.destroy'
    ]);
    $router->bind('orderrefund', function ($id) {
        return app('Modules\Sale\Repositories\OrderRefundRepository')->find($id);
    });
    $router->get('orderrefunds', [
        'as' => 'admin.sale.orderrefund.index',
        'uses' => 'OrderRefundController@index',
        'middleware' => 'can:sale.orderrefunds.index'
    ]);
    $router->get('orderrefunds/create', [
        'as' => 'admin.sale.orderrefund.create',
        'uses' => 'OrderRefundController@create',
        'middleware' => 'can:sale.orderrefunds.create'
    ]);
    $router->post('orderrefunds', [
        'as' => 'admin.sale.orderrefund.store',
        'uses' => 'OrderRefundController@store',
        'middleware' => 'can:sale.orderrefunds.create'
    ]);
    $router->get('orderrefunds/{orderrefund}/edit', [
        'as' => 'admin.sale.orderrefund.edit',
        'uses' => 'OrderRefundController@edit',
        'middleware' => 'can:sale.orderrefunds.edit'
    ]);
    $router->put('orderrefunds/{orderrefund}', [
        'as' => 'admin.sale.orderrefund.update',
        'uses' => 'OrderRefundController@update',
        'middleware' => 'can:sale.orderrefunds.edit'
    ]);
    $router->delete('orderrefunds/{orderrefund}', [
        'as' => 'admin.sale.orderrefund.destroy',
        'uses' => 'OrderRefundController@destroy',
        'middleware' => 'can:sale.orderrefunds.destroy'
    ]);
    $router->bind('orderreturn', function ($id) {
        return app('Modules\Sale\Repositories\OrderReturnRepository')->find($id);
    });
    $router->get('orderreturns', [
        'as' => 'admin.sale.orderreturn.index',
        'uses' => 'OrderReturnController@index',
        'middleware' => 'can:sale.orderreturns.index'
    ]);
    $router->get('orderreturns/create', [
        'as' => 'admin.sale.orderreturn.create',
        'uses' => 'OrderReturnController@create',
        'middleware' => 'can:sale.orderreturns.create'
    ]);
    $router->post('orderreturns', [
        'as' => 'admin.sale.orderreturn.store',
        'uses' => 'OrderReturnController@store',
        'middleware' => 'can:sale.orderreturns.create'
    ]);
    $router->get('orderreturns/{orderreturn}/edit', [
        'as' => 'admin.sale.orderreturn.edit',
        'uses' => 'OrderReturnController@edit',
        'middleware' => 'can:sale.orderreturns.edit'
    ]);
    $router->put('orderreturns/{orderreturn}', [
        'as' => 'admin.sale.orderreturn.update',
        'uses' => 'OrderReturnController@update',
        'middleware' => 'can:sale.orderreturns.edit'
    ]);
    $router->delete('orderreturns/{orderreturn}', [
        'as' => 'admin.sale.orderreturn.destroy',
        'uses' => 'OrderReturnController@destroy',
        'middleware' => 'can:sale.orderreturns.destroy'
    ]);
    $router->bind('comment', function ($id) {
        return app('Modules\Sale\Repositories\CommentRepository')->find($id);
    });
    $router->get('comments', [
        'as' => 'admin.sale.comment.index',
        'uses' => 'CommentController@index',
        'middleware' => 'can:sale.comments.index'
    ]);
    $router->get('comments/create', [
        'as' => 'admin.sale.comment.create',
        'uses' => 'CommentController@create',
        'middleware' => 'can:sale.comments.create'
    ]);
    $router->post('comments', [
        'as' => 'admin.sale.comment.store',
        'uses' => 'CommentController@store',
        'middleware' => 'can:sale.comments.create'
    ]);
    $router->get('comments/{comment}/edit', [
        'as' => 'admin.sale.comment.edit',
        'uses' => 'CommentController@edit',
        'middleware' => 'can:sale.comments.edit'
    ]);
    $router->put('comments/{comment}', [
        'as' => 'admin.sale.comment.update',
        'uses' => 'CommentController@update',
        'middleware' => 'can:sale.comments.edit'
    ]);
    $router->delete('comments/{comment}', [
        'as' => 'admin.sale.comment.destroy',
        'uses' => 'CommentController@destroy',
        'middleware' => 'can:sale.comments.destroy'
    ]);


});
