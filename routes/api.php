<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|*/



$api = app('Dingo\Api\Routing\Router');
$api->version(['v1'],function ($api){
    $api->group(['middleware' => 'jwt.refresh', 'namespace' => 'App\Api\Controller'], function ($api){
        $api->get('lessons', 'LessonController@index');
        $api->get('lesson/{lesson}', 'LessonController@show');
    });

    $api->group([
        'prefix' => 'auth',
        'namespace' => 'App\Api\Controller'
    ], function ($api) {
        $api->post('login', 'AuthController@login');
        $api->post('logout', 'AuthController@logout');
        $api->post('refresh', 'AuthController@refresh');
        $api->post('me', 'AuthController@me');
        $api->post('payload', 'AuthController@payload');
    });
});




// client credentials grant token
Route::get('lesson5', 'UserController@lesson5')->middleware('client');


Route::group(['middleware' =>['scopes:lesson1,lesson5']], function (){
    Route::get('lesson1', 'UserController@lesson1')->middleware('auth:api');
});

Route::group(['middleware' => ['scope:lesson1,lesson3', 'auth:api']], function(){
    Route::get('lesson2', 'UserController@lesson2');
    Route::get('lesson3', 'UserController@lesson3');
    Route::get('lesson4', 'UserController@lesson4');
    Route::get('lesson5', 'UserController@lesson5');
    Route::get('lesson6', 'UserController@lesson6');
});

Route::get('lesson7', 'UserController@lesson7')->middleware('auth:api');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->post('/user', function (Request $request) {
    return $request->user();
});


//$api->version('v2', function ($api){
//    $api->group(['namespace' => 'App\Api\Controller'], function($api){
//       $api->get('lessons', 'LessonController@index');
//    });
//});

// 基础路由
//Route::group([
//    'prefix' => 'auth'
//], function ($router) {
//    Route::post('login', 'AuthController@login');
//    Route::post('logout', 'AuthController@logout');
//    Route::post('refresh', 'AuthController@refresh');
//    Route::post('me', 'AuthController@me');
//});

// Lesson 路由
// Route::group(['middleware' => 'jwt.auth'], function(){
//     Route::resource('lesson', 'LessonController');
// });
