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

//    $router->get('saleorders/create', [
//        'as' => 'admin.sale.saleorder.create',
//        'uses' => 'SaleOrderController@create',
//        'middleware' => 'can:sale.saleorders.create'
//    ]);
//    $router->post('saleorders', [
//        'as' => 'admin.sale.saleorder.store',
//        'uses' => 'SaleOrderController@store',
//        'middleware' => 'can:sale.saleorders.create'
//    ]);
//    $router->get('saleorders/{saleorder}/edit', [
//        'as' => 'admin.sale.saleorder.edit',
//        'uses' => 'SaleOrderController@edit',
//        'middleware' => 'can:sale.saleorders.edit'
//    ]);
//    $router->put('saleorders/{saleorder}', [
//        'as' => 'admin.sale.saleorder.update',
//        'uses' => 'SaleOrderController@update',
//        'middleware' => 'can:sale.saleorders.edit'
//    ]);
    $router->delete('saleorders/{saleorder}', [
        'as' => 'admin.sale.saleorder.destroy',
        'uses' => 'SaleOrderController@destroy',
        'middleware' => 'can:sale.saleorders.destroy'
    ]);
// append

});
