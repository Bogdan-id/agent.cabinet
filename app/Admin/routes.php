<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->get('/user/activate/{id}', 'AgentController@activateUser');
    $router->get('/user/deactivate/{id}', 'AgentController@deactivateUser');
    $router->post('/agent/update/{id}', 'AgentController@adminUpdateAgent');
    $router->get('/agent/all', 'AgentController@getAgents');
    $router->get('/getDontActiveUsers', 'AgentController@getDontActiveUsers');

});
