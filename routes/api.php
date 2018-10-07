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
//    $api->group(['middleware' => 'auth.basic.once'], function ($api){
        $api->resource('lesson', 'App\Http\Controllers\LessonController');
//    });
});


$api->version('v2', function ($api){
    $api->group(['namespace' => 'App\Api\Controller'], function($api){
       $api->get('lessons', 'LessonController@index');
    });
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});