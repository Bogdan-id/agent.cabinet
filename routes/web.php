<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/getUserAgent', 'AgentsController@getUserAgent');
Route::post('/agent/create', 'AgentsController@create');
Route::get('/agent/manager/{agent_id}', 'AgentsController@getAgentManager')
            ->where('agent_id', '[0-9]+');

Route::get('calculations/agent/{id}', 'CalculateController@getCalculationByAgent');
Route::get('calculation/{id}', 'CalculateController@getCalculationById');

Route::post('leasing-reqeust/create', 'LeasingRequestController@create');
Route::get('leasing-reqeust/{id}', 'LeasingRequestController@getLeasingRequest')
            ->where('id', '[0-9]+');
Route::get('leasing-reqeust/agent/{agent_id}', 'LeasingRequestController@getLeasingRequestByAgent')
            ->where('agent_id', '[0-9]+');

Route::get('/getManagers', 'ManagerController@getManagers');
Route::get('/updateManagers', 'ManagerController@updateManagers');

Route::get('/getCurrentUser', 'UserController@getCurrentUser');

Route::post('/calculate', 'CalculateController@create');
Route::get('/models', 'ModelController@index');
Route::get('/mark', 'MarkController@index');
