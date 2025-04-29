<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\ProductCategory;
use App\Models\Tag;
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
        if (!request()->is('admin') && !request()->is('admin/*')) {
            view()->composer('*', function ($view) {
                $shop_categories_menu = Cache::rememberForever('shop_categories_menu', function () {
                    return ProductCategory::tree();
                });

                $shop_tags_menu = Cache::rememberForever('shop_tags_menu', function () {
                    return Tag::whereStatus(true)->get();
                });

                $view->with([
                    'shop_categories_menu' => $shop_categories_menu,
                    'shop_tags_menu' => $shop_tags_menu,
                ]);
            });
        }



        // In your AppServiceProvider's boot() method
        if ((request()->is('admin') || request()->is('admin/*')) && !request()->is('admin/login')) {
            view()->composer('*', function ($view) {
                $admin_side_menu = Cache::rememberForever('admin_side_menu', function () {
                    return Permission::tree();
                });
                $view->with('admin_side_menu', $admin_side_menu);
            });
        }
    }
}
