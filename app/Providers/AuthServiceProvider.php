<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     * @return void
     */
    public function boot ()
    {
        $this->registerPolicies();

        Auth::provider('custom', function ($app, array $config)
        {
            return new CustomUserProvider($app['hash'], $config['model']);
        });

        Gate::define('update-schedule', function (User $user, array $input)
        {
            $permissions = Role::find($user->id_role)->permissions()
                               ->pluck('permission.id')->toArray();
            return in_array(isset($input['note']) ? 10 : 14, $permissions);
        });
    }
}
