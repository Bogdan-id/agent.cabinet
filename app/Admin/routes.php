<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->get('/user/activate/{id}', '\App\Http\Controllers\UserController@activateUser');
    $router->post('/agent/update/{id}', '\App\Http\Controllers\AgentsController@adminUpdateAgent');

});
