<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['prefix' => 'tokens'], function(){
    Route::get('clients', 'TokenController@clients');
    Route::get('authorized', 'TokenController@authorized');
    Route::get('tokens', 'TokenController@accessTokens');
});


//Route::get('/home', 'HomeController@index')->name('home');
//

Route::get('remind', 'UserController@store');

// Personal access_token
// eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjNkYmI0Mjg5NTJiOWI3ZDRjZWViYjc1YWZjYzkxM2E5MzJhNjUwN2IzN2RmZTdhMTk2ZjFlZDY2NWI5ODY0ZmZjYTIwYzc0Y2U4N2Q2MTc4In0.eyJhdWQiOiIxMCIsImp0aSI6IjNkYmI0Mjg5NTJiOWI3ZDRjZWViYjc1YWZjYzkxM2E5MzJhNjUwN2IzN2RmZTdhMTk2ZjFlZDY2NWI5ODY0ZmZjYTIwYzc0Y2U4N2Q2MTc4IiwiaWF0IjoxNTQwMjExMjMyLCJuYmYiOjE1NDAyMTEyMzIsImV4cCI6MTU3MTc0NzIzMiwic3ViIjoiMSIsInNjb3BlcyI6W119.LcSMk7R3rJVJyytL3p-hEJlld5wPb1LRSe4WOMOgLReluKkYK4E-1wAwVSxQfrwsD0d9dQyzSRSLbXOb0Ef0Ys1BdWfq0Br3L_rUZUp92Rf-nra7bnA5WgzZNqVXes1B1x_QZXT0ASzTDgDJEJ7djDEebv3NxMj5w6ETpIXqT11ABehygqfBjCy5oBMTN0tFER2GCE4HIQmIeu-oCxQ26U146gqVNm686mP9s8zybp45q3wyr1BcsPBnXvahj24c2pSiOSC_ZgbrfPTDBmWjHMWFZvtcbJ4RYntw1I-lIi7ZSrfm4Vp9P-hoNKnmkQQe_gm4wO-VgXMj5nu2uuAYsktu_93MMyi6PzVSJrKTs4xujEhxofSP9Mc3_P0l5P-Mz55Z1PiEU8jGw0dtSysGwczNxzEY-CsFSxuYdUNd553mpyw-ALmMAcJVYLdzuMLXGp9v1qt8xPM5t7Bg7s_dtvOKjXxEcKeUxM-kXB_JL8_F3-hl1p2fuOY_lWhJES_gclZn_sYWUde7ksO7NyZ5RVo6dEoodf_i7OKzoRoS53wdlm5_Lb_oSIgyl1oGrgAHgOv2aRfFUyGyR83XdBb5CbpWgKsnBCMN3d-v-mDH1Y79Yrc1vCDxd6g66HZ-w1naEhdxXEZe2Mlq63qzq2WF7vPOpjKxJh7dsItXprXcBQs

//Route::get('lesson2', 'UserController@lesson2')->middleware('auth');
//Route::get('lesson3', 'UserController@lesson3')->middleware('auth');
//Route::get('lesson4', 'UserController@lesson4')->middleware('auth');
//Route::get('lesson5', 'UserController@lesson5')->middleware('auth');
//Route::get('lesson6', 'UserController@lesson6')->middleware('auth');
//Route::get('lesson7', 'UserController@lesson7')->middleware('auth');
//Route::get('test', 'UserController@test')->middleware('auth');






