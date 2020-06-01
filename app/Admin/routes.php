<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    //'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->get('/user/activate/{id}', 'AgentController@activateUser');
    $router->get('/user/deactivate/{id}', 'AgentController@deactivateUser');
    $router->post('/agent/update/{id}', 'AgentController@adminUpdateAgent');
    $router->get('/agent/all', 'AgentController@getAgents');
    $router->get('/getDontActiveUsers', 'AgentController@getDontActiveUsers');
    $router->get('useful-materials-categories/all', 'UsefulMaterialsController@getCategories');
    $router->post('useful-materials-categories/create', 'UsefulMaterialsController@createCategory');
    $router->post('useful-materials-categories/update/{id}', 'UsefulMaterialsController@updateCategory')
            ->where('id', '[0-9]+');
    $router->delete('useful-materials-categories/delete/{id}', 'UsefulMaterialsController@destroyCategory')
            ->where('id', '[0-9]+');
    $router->post('useful-material/create', 'UsefulMaterialsController@createMaterial');
    $router->post('useful-material/update/{id}', 'UsefulMaterialsController@updateMaterial')
            ->where('id', '[0-9]+');
    $router->delete('useful-material/delete/{id}', 'UsefulMaterialsController@destroyMaterial')
            ->where('id', '[0-9]+');
    $router->post('useful-material/image/upload', 'UsefulMaterialsController@uploadImage');
    $router->get('managers/update', 'ManagersController@updateManagers');
    $router->get('useful-materials/all', 'UsefulMaterialsController@getMaterials');
    $router->get('useful-materials/category/{id}', 'UsefulMaterialsController@getMaterialsByCategory');

});
