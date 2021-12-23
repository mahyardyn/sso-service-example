<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->permission();

        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->input('api_token')) {
                return User::where('api_token', $request->input('api_token'))->first();
            }
        });
    }

    public function permission()
    {
        // Get all permission
        $permissions = Permission::all()->pluck('name');

        foreach ($permissions as $permission)
            Gate::define($permission, function ($user) use ($permission) {

                // Check user permission exist 
                if (!$user->permission)
                    return false;

                // Get all permission users
                $userPermission = $user->permission->permissions;

                // Check permission
                return in_array($permission, $userPermission);
            });
    }
}
