<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');

    //Crawler Router
    Route::group(['prefix' => 'crawler'], function ($router) {
        $router->resource('task', Crawler\TaskController::class);
        $router->resource('config', Crawler\ConfigController::class);
        $router->resource('log', Crawler\LogController::class);
    });

});
