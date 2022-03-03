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
            return in_array(10, $permissions);
        });

        Gate::define('get-department-fixed-schedule', function (User $user)
        {
            $permissions = Role::find($user->id_role)->permissions()
                               ->pluck('permission.id')->toArray();
            return in_array(30, $permissions);
        });

        Gate::define('get-fixed-schedule', function (User $user)
        {
            $permissions = Role::find($user->id_role)->permissions()
                               ->pluck('permission.id')->toArray();
            return in_array(31, $permissions);
        });

        Gate::define('create-fixed-schedule', function (User $user, array $input)
        {
            $permissions = Role::find($user->id_role)->permissions()
                               ->pluck('permission.id')->toArray();
            return in_array(($input['status'] != 4 ? ($input['status'] == 0 ? 12 : -1) : 14),
                $permissions);
        });

        Gate::define('update-fixed-schedule', function (User $user, array $input)
        {
            $permissions = Role::find($user->id_role)->permissions()
                               ->pluck('permission.id')->toArray();
            return in_array((in_array($input['status'], [2, -2])
                ? 17 : (in_array($input['status'], [3, 1, -1])
                    ? 13 : -1)), $permissions);
        });

        Gate::define('get-teacher-schedule', function (User $user)
        {
            $permissions = Role::find($user->id_role)->permissions()
                               ->pluck('permission.id')->toArray();

            return in_array(6, $permissions);
        });

        Gate::define('get-department-schedule', function (User $user)
        {
            $permissions = Role::find($user->id_role)->permissions()
                               ->pluck('permission.id')->toArray();

            return in_array(7, $permissions);
        });

        Gate::define('get-teacher-exam-schedule', function (User $user)
        {
            $permissions = Role::find($user->id_role)->permissions()
                               ->pluck('permission.id')->toArray();

            return in_array(5, $permissions);
        });

        Gate::define('get-department-exam-schedule', function (User $user)
        {
            $permissions = Role::find($user->id_role)->permissions()
                               ->pluck('permission.id')->toArray();

            return in_array(8, $permissions);
        });

        Gate::define('update-exam-schedule', function (User $user)
        {
            $permissions = Role::find($user->id_role)->permissions()
                               ->pluck('permission.id')->toArray();

            return in_array(11, $permissions);
        });
    }
}
