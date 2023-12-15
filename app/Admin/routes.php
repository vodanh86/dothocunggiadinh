<?php


use App\Admin\Controllers\CommunicationController;
use Encore\Admin\Facades\Admin;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('/branch', ABranchController::class);
    $router->resource('/product-group', AProductGroupController::class);
    $router->resource('/category', ACategoryController::class);
    $router->resource('/product', AProductController::class);
    $router->resource('/social-information', ASocialInformationController::class);
    $router->resource('/sell-information', ASellInformationController::class);
    $router->resource('/contact', AContactController::class);
    $router->resource('/delivery-system', ADeliverySystemController::class);
    $router->resource('/news', ANewsController::class);
    $router->resource('/events', AEventsController::class);
    $router->resource('/events', AEventsController::class);
    $router->resource('/system-information', ASystemInformationController::class);

});
