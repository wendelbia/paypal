<?php

namespace App\Providers;


use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Model\Order;

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
        //se o usu dono da ordem, passo os dois params
        Gate::define('owner-order', function($user, $order){
            //retorna retorna o usuário da ordem q é o mesmo logado
            return $order->user_id == $user->id;
        });
        //
    }
}
