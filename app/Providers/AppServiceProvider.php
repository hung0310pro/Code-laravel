<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Tinify\setKey("94B7stcM86zMYLwJxj5tYdvh7gLxln8F");
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);
    }
}