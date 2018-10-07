<?php


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::get('/home', 'HomeController@index')->name('home');

Route::get('remind', 'UserController@store');
Route::get('lesson1', 'UserController@lesson1');
Route::get('lesson2', 'UserController@lesson2')->middleware('auth');
Route::get('lesson3', 'UserController@lesson3')->middleware('auth');
Route::get('lesson4', 'UserController@lesson4')->middleware('auth');
Route::get('lesson5', 'UserController@lesson5')->middleware('auth');
Route::get('lesson6', 'UserController@lesson6')->middleware('auth');
Route::get('lesson7', 'UserController@lesson7')->middleware('auth');
Route::get('test', 'UserController@test')->middleware('auth');





