<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
        Passport::routes();

        // 15天失效
        Passport::tokensExpireIn(now()->addDays(15));

        // set when refresh tokens expire 30天
        Passport::refreshTokensExpireIn(now()->addDays(30));

        // implicit grant token
        Passport::enableImplicitGrant();

        // 设置 scope
        Passport::tokensCan([
            'place-orders' => '下订单',
            'check-status' => '检查运单的状态',
            'lesson1' => '课程1',
            'lesson2' => '课程2',
            'lesson3' => '课程3',
            'lesson4' => '课程4',
            'lesson5' => '课程5',
            'lesson6' => '课程6',
            'lesson7' => '课程7',
        ]);
    }
}
