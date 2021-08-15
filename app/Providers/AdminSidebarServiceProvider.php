<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AdminSidebarServiceProvider extends ServiceProvider
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
        view()->composer('admin.*', 'App\Http\Composers\AdminSidebarComposer');
    }
}
