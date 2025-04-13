<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // In your AppServiceProvider's boot() method
        if (request()->is('admin') || request()->is('admin/*')) {
            view()->composer('*', function ($view) {
                $admin_side_menu = Cache::rememberForever('admin_side_menu', function () {
                    return Permission::tree();
                });

                $view->with('admin_side_menu', $admin_side_menu);
            });
        }
    }
}
