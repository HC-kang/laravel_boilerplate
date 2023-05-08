<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::tokensCan([
            'super-admin' => 'Super Admin API Call',
            'admin'       => 'Admin API Call',
            'normal'      => 'normal API Call',
        ]);

        $expiresAt = now()->addDays(env('TOKEN_EXPIRE_DAYS', 1));
        
        Passport::tokensExpireIn($expiresAt);
        Passport::refreshTokensExpireIn($expiresAt);
        Passport::personalAccessTokensExpireIn($expiresAt);
    }
}
