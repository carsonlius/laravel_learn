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
    $api->group(['middleware' => 'jwt.auth', 'namespace' => 'App\Api\Controller'], function ($api){
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
    });
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
