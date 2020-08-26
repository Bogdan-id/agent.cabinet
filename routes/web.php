<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/verification',  function () {
    return view('verification');
})->middleware('auth', 'dont_user_active');

Route::get('/finish-register', function () {
    return view('create_agent');
})->middleware(['auth', 'user_active', 'dont_user_agent']);

Route::get('/verification/accept/{id}', 'VerificationController@acceptAgent');
Route::get('/verification/reject/{id}', 'VerificationController@rejectAgent');

Route::post('password/reset/sendSms', 'Auth\ResetPasswordSmsController@sendCodeMessage');
Route::post('password/reset/resetPassword', 'Auth\ResetPasswordSmsController@resetPassword');

Route::get('/getUserAgent', 'AgentsController@getUserAgent');
Route::post('/agent/create', 'AgentsController@create');
Route::post('/agent/update/{id}', 'AgentsController@update')
            ->where('id', '[0-9]+');
Route::post('/agent/searchContact', 'AgentsController@getAgentContact');

Route::get('/agent/manager/{agent_id}', 'AgentsController@getAgentManager')
            ->where('agent_id', '[0-9]+');
Route::get('/agent/notifications/{agent_id}', 'NotificationsController@getAgentNotifications')
            ->where('agent_id', '[0-9]+');
Route::delete('/agent/notifications/delete/{id}', 'NotificationsController@delete')
            ->where('id', '[0-9]+');
Route::post('/agent/notifications/checking', 'NotificationsController@checkingNotifications');
Route::get('calculations/agent/{id}', 'CalculateController@getCalculationByAgent');
Route::get('calculation/{id}', 'CalculateController@getCalculationById');
Route::delete('calculation/delete/{id}', 'CalculateController@delete');
Route::post('calculation/getPdf', 'CalculateController@createExcel');

Route::post('leasing-reqeust/create', 'LeasingRequestController@create');
Route::get('leasing-reqeust/{id}', 'LeasingRequestController@getLeasingRequest')
            ->where('id', '[0-9]+');
Route::get('leasing-reqeust/agent/{agent_id}', 'LeasingRequestController@getLeasingRequestByAgent')
            ->where('agent_id', '[0-9]+');
Route::post('leasing-reqeust/document/upload', 'LeasingRequestController@documetnUpload');
Route::get('leasing-reqeust/company/{edrpou}', 'LeasingRequestController@getCompanyName');


Route::get('/getAcquisitionTargets', 'AcquisitionTargetController@getAcquisitionTargets');

Route::get('useful-materials-categories/all', 'UsefulMaterialsController@getCategories');
Route::post('feedback/send', 'FeedbackController@sendFeedBack');

Route::post('agent-commission/create', 'AgentCommisionController@create');
Route::get('agent-commission/agent/{id}', 'AgentCommisionController@getAgentCommissions');

Route::get('/getManagers', 'ManagerController@getManagers');
Route::get('/updateManagers', 'ManagerController@updateManagers');
Route::get('/reports/leasing-requests/{agentId}/{year}', 'ReportsController@leasingrequestsReport');
Route::get('/reports/agent-commissions/{agentId}/{year}', 'ReportsController@agentCommissionsReport');

Route::get('/getCurrentUser', 'UserController@getCurrentUser');
Route::get('json/slides', 'SliderController@getSlides');
Route::get('json/slides/{slug}', 'SliderController@getSlide');
Route::get('json/news', 'NewsController@getAllNews');
Route::get('json/news/{slug}', 'NewsController@getNews');
Route::get('json/useful-materials-categories/{slug}', 'UsefulMaterialsController@getMaterialsByCategory');
Route::get('json/useful-materials-categories/{categorySlug}/{slug}', 'UsefulMaterialsController@getMaterialBySlug');
Route::post('/calculate', 'CalculateController@create');
Route::get('/models', 'ModelController@index');
Route::get('/mark', 'MarkController@index');
Route::get('bitrix/updatedLead', 'BitrixController@getUpdatedLeadId');
Route::get('/fire', function () {
        event(new \App\Events\NewDataEvent('data'));
        return 'ok';
    });
Route::group([
    'prefix'        => 'admin',
    'namespace'     => '\App\\Admin\\Controllers',
    'middleware'    => ['web', 'isadmin'],
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