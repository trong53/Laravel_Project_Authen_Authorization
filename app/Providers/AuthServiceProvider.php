<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\{User, Module, Post, Group};
use App\Policies\{PostPolicy, UserPolicy, GroupPolicy};

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Post::class => PostPolicy::class,
        User::class => UserPolicy::class,
        Group::class => GroupPolicy::class
    ];


    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Define Gate for enter a Module (general entry)
        // in permissions : if have Module, Entry is OK

        $modules = Module::all();           // get all modules

        foreach ($modules as $module) {     // check every module => create Gate for each Module (name of Gate = Module->name)

            Gate::define($module->name, function(User $user) use ($module) {
                $permissions = $user->group->permissions;
                
                if (!empty($permissions)) {
                    $permissionArray = json_decode($permissions, true);
                } else {
                    $permissionArray = [];
                }
                
                if (in_array($module->name, array_keys($permissionArray))) {  // check if module name is the key of permission Array
                    return true;
                }

                return false;
            });
        }
        
    }
}
