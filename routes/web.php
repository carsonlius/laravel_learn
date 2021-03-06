<?php

Route::get('/', function () {
    \App\Article::trading()->get();
    return view('welcome');
});

Route::get('home', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['prefix' => 'tokens'], function () {
    Route::get('clients', 'TokenController@clients');
    Route::get('authorized', 'TokenController@authorized');

    // token 列表
    Route::get('tokens', 'TokenController@accessTokens');
});

Route::get('remind', 'UserController@store');

// Personal access_token （没有scopes）
// eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjNkYmI0Mjg5NTJiOWI3ZDRjZWViYjc1YWZjYzkxM2E5MzJhNjUwN2IzN2RmZTdhMTk2ZjFlZDY2NWI5ODY0ZmZjYTIwYzc0Y2U4N2Q2MTc4In0.eyJhdWQiOiIxMCIsImp0aSI6IjNkYmI0Mjg5NTJiOWI3ZDRjZWViYjc1YWZjYzkxM2E5MzJhNjUwN2IzN2RmZTdhMTk2ZjFlZDY2NWI5ODY0ZmZjYTIwYzc0Y2U4N2Q2MTc4IiwiaWF0IjoxNTQwMjExMjMyLCJuYmYiOjE1NDAyMTEyMzIsImV4cCI6MTU3MTc0NzIzMiwic3ViIjoiMSIsInNjb3BlcyI6W119.LcSMk7R3rJVJyytL3p-hEJlld5wPb1LRSe4WOMOgLReluKkYK4E-1wAwVSxQfrwsD0d9dQyzSRSLbXOb0Ef0Ys1BdWfq0Br3L_rUZUp92Rf-nra7bnA5WgzZNqVXes1B1x_QZXT0ASzTDgDJEJ7djDEebv3NxMj5w6ETpIXqT11ABehygqfBjCy5oBMTN0tFER2GCE4HIQmIeu-oCxQ26U146gqVNm686mP9s8zybp45q3wyr1BcsPBnXvahj24c2pSiOSC_ZgbrfPTDBmWjHMWFZvtcbJ4RYntw1I-lIi7ZSrfm4Vp9P-hoNKnmkQQe_gm4wO-VgXMj5nu2uuAYsktu_93MMyi6PzVSJrKTs4xujEhxofSP9Mc3_P0l5P-Mz55Z1PiEU8jGw0dtSysGwczNxzEY-CsFSxuYdUNd553mpyw-ALmMAcJVYLdzuMLXGp9v1qt8xPM5t7Bg7s_dtvOKjXxEcKeUxM-kXB_JL8_F3-hl1p2fuOY_lWhJES_gclZn_sYWUde7ksO7NyZ5RVo6dEoodf_i7OKzoRoS53wdlm5_Lb_oSIgyl1oGrgAHgOv2aRfFUyGyR83XdBb5CbpWgKsnBCMN3d-v-mDH1Y79Yrc1vCDxd6g66HZ-w1naEhdxXEZe2Mlq63qzq2WF7vPOpjKxJh7dsItXprXcBQs
// lesson1,2,5
// eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImJmYTRiZTk4MjAwNjcyNDE4NjcwZDExMzRmMGNiYzlkMmUyNTRiMGE5YWNkOTVhNzIzZDQzMTMwYmM3Y2RjNzgzNzI5YTcwNDEyNDI5MmU3In0.eyJhdWQiOiIxMCIsImp0aSI6ImJmYTRiZTk4MjAwNjcyNDE4NjcwZDExMzRmMGNiYzlkMmUyNTRiMGE5YWNkOTVhNzIzZDQzMTMwYmM3Y2RjNzgzNzI5YTcwNDEyNDI5MmU3IiwiaWF0IjoxNTQwMjk1MjE2LCJuYmYiOjE1NDAyOTUyMTYsImV4cCI6MTU3MTgzMTIxNiwic3ViIjoiMSIsInNjb3BlcyI6WyJsZXNzb24xIiwibGVzc29uMiIsImxlc3NvbjUiXX0.zb560EUag3eyEA4WWubN4iQno6bxux7NiCcDIU7oXZIOwKxVNLc1FXyKVieXbZnzeiafv_FtupTAmHnvQ-7CIWU0KSbgjwFOLJA8CIQjRqMs7z9HRqBAwntozSnuBxkfF3rCzqE49HNzu0bDrPO83vjHzEnjrakGqJdZ4sOlW5zCUNt_PPNfPO8-8HDKB_zR_4vs-YZ-ejt9t8W4aJ-jrolWkdu3GSF35TKVg7eDYaCZUHhnSoj3AtgMx496yBaHrGDq3EckaTbP8IC_OQy0SiZCmr6osn1vfIF3CgBXNi5rVXWsddDBcUY9_3bTXXq1CVb2nwWySM9mvGpRrELbyH3-fBFpgSZzVLb3Zuo3Jmyh4Bb0GLh3IQ3W-N8raav1v6PP6VpcZwOmEo1KU7mLUHT6mXAcPJnUuScncQalYxQEjTO2LsC4ypRCWp1Lqx8PdG9UIud0HDquPej98qIhkX0HpY3wKjN_WWSX9Q8MUoO4UwrP1z6Emal8-S4R83xXk-HWGXTLWMwrW3GBdIBdnPbtNdjrRY23mK0lrMw21vQpZcoWfmWqFWUmLSBGYJ-jKLzApNLxJbVJ8AWzkGimuuZgMqvJhGh9YtWBoz4M8eqvBGlYNbmukQ4Y7BuBHHhxJFFbMOY6DOcurUytin2cdl5kD0ub9LF3lk-hnyqfycc
// lesson 3
// eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjkyMDE2NWI0MzhiMWM2Y2Y4NTUzYzczMTBmY2I4ZmRkNjFkOTUyYjBmOWFiZDQwZGRkNzY4YTRiOWQ4ZGRjNzZkODEyNDI0NmFjYmY0M2U4In0.eyJhdWQiOiIxMCIsImp0aSI6IjkyMDE2NWI0MzhiMWM2Y2Y4NTUzYzczMTBmY2I4ZmRkNjFkOTUyYjBmOWFiZDQwZGRkNzY4YTRiOWQ4ZGRjNzZkODEyNDI0NmFjYmY0M2U4IiwiaWF0IjoxNTQwMjk2Mjk2LCJuYmYiOjE1NDAyOTYyOTYsImV4cCI6MTU3MTgzMjI5Niwic3ViIjoiMSIsInNjb3BlcyI6WyJsZXNzb24zIl19.fEDEFpB4fK_PQGNENN_JNa4pndSINXJUFfI_wvnqrX5_VlPRAXg5J_Y04JDS7prowHIHh65kNkKF_2f4gDd2VcspYItwlZsi5F2uk6ufEYfxS-EZroRlNtJCnASkO2qIlYqQr57PJmOkEmCpowarg-DZhyeXKPaORoGbvh0FFviGtvgzvHGmhAynBp1X1DmRjpO6ziQKX_IK1px4fvhJ0rfvyIivbhdwrcz7RIcQebApp-sNEvX-AAkbKiSfy7bpHt01TnGb2Jw7vp1xiSS1qGXkFr4LB8RYoEwbQduGoM6nHVs7RUxMdT5zY_3gIUNBMpkRdEob8yE9A-x0_3-sXvpmNw28ZPOQGB7JRv9SS9AdRNJLgUMUycE2r-9_BO4wCGI_jTB-WfImKmZP3jyqVZixscUB1d9aAjY-n83H0wewzVHIoXvANOMyBDNjtKw7njciNOvLjXNJMN0pl9U6z7iMeFfu17PrWpJMGvlabSrIloQMlZC0eMg1CzcAPF6RItVfN02_Uqkh6SGpjEZ00aYFQ9bijKWW3Ot9gqaDq9Zj6fsYobLoufrQcBjxit1Zo6qE2Vwsc0tEJT7ePjkWZYZbXbT9CrS8TGPYXwf0lUBq2q_VG_F2NlSnbBjkKua9TL03mTayn3pKCcxStbK9e1zsTzuf90ZwahWL4rob1Vg


Route::get('/passport_web/show', 'UserController@passportWeb')->middleware('auth');

//Route::any('/wechat', 'WeChatController@serve');


Route::get('/private', function(\Illuminate\Http\Request $request){
    dd($request->session()->get('today'));
});


Route::get('/privateT', function (){
    \App\Events\ClearUserUpdatedEvent::dispatch(\App\User::find(1));
    dump('已经完成了对私有渠道的触发');
});


Route::get('notification', function(){
    Notification::send(\App\User::find(1), new \App\Notifications\InvoicePaid(\App\Post::find(1)));

    $notifications = \App\User::find(1)->unreadNotifications;
    foreach ($notifications as $notification) {
        dump('ID :' . $notification->id . 'Type :' . $notification->type . ' title :' . $notification->data['post_title']);
    }
});


Route::get('test', function (){
    $team = factory(App\Team::class)->create();
    $user = factory(App\User::class)->create();
    $user_two = factory(App\User::class)->create();
    $team->add($user);
    $team->add($user_two);
    dump($team->toArray(), $team->members, $team->count());

//    $team = \App\Team::find(87);
//    dump($team->members->toArray(), count($team->members->toArray()), $team->members);
});

Route::get('test2', function(){
    return 'test2 page';
});

Route::get('test3', function(){
    return redirect('test2');
});

Route::post('test', function(){

    return request()->post();
//    return request()->header();
//    return request()->query();
//    return [file_get_contents('php://input')];
//    return [request()->has(['what'])];
//    return request()->all();
//    return $_POST;
});

Route::resource('test_c', 'TestController');

