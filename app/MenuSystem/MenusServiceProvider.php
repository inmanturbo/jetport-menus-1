<?php

namespace App\MenuSystem;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MenusServiceProvider extends ServiceProvider
{
    public function register()
    {
        // $this->mergeConfigFrom(__DIR__.'/config/menus_config.php', 'menus');
    }

    public function boot()
    {
        Relation::morphMap([User::class]);
        View::composer('frontend.layouts.menus', MenusLayoutComposer::class);
        MenuGates::define();
        //$this->loadMigrationsFrom(__DIR__.'/Database/migrations');
        $this->loadRoutesFrom(__DIR__.'/menus_routes.php');
        //$this->loadViewsFrom(__DIR__.'/views', 'menus');
    }
}
