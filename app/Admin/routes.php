<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->group(['prefix' => 'crawler', 'namespace' => 'Crawler'], function (Router $router) {
        $router->get('/', 'HomeController@index')->name('crawler.home');
        $router->resource('task', 'TaskController');
        $router->resource('visit', 'VisitController');
        $router->resource('content', 'ContentController');
    });

});
