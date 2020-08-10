<?php

use Illuminate\Routing\Router;

// Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => 'isadmin',
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
    $router->post('image/upload', 'ImagesController@uploadImage');
    $router->post('image/delete', 'ImagesController@deleteImage');
    $router->get('managers/update', 'ManagersController@updateManagers');
    $router->get('managers/all', 'ManagersController@getManagers');
    $router->get('useful-materials/all', 'UsefulMaterialsController@getMaterials');
    $router->get('useful-materials/category/{id}', 'UsefulMaterialsController@getMaterialsByCategory');

    $router->get('slide/all', 'SliderController@getSlides');
    $router->post('slide/create', 'SliderController@createSlide');
    $router->post('slide/update/{id}', 'SliderController@updateSlide')
            ->where('id', '[0-9]+');
    $router->delete('slide/delete/{id}', 'SliderController@destroySlide')
            ->where('id', '[0-9]+');

    $router->get('news/all', 'NewsController@getNews');
    $router->post('news/create', 'NewsController@createNews');
    $router->post('news/update/{id}', 'NewsController@updateNews')
                ->where('id', '[0-9]+');
    $router->delete('news/delete/{id}', 'NewsController@destroyNews')
                ->where('id', '[0-9]+');

    $router->get('agent-commission/all', 'AgentCommisionController@getAgentCommisions');
    $router->get('agent-commission/paid/{id}', 'AgentCommisionController@paidAgentCommision')
                ->where('id', '[0-9]+');
});

Route::get('admin/login', 'App\Admin\Controllers\AuthController@showLoginForm');
Route::post('admin/login', 'App\Admin\Controllers\AuthController@login');
Route::post('admin/logout', 'App\Admin\Controllers\AuthController@logout');
