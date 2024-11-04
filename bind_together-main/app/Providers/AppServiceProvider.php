<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
        Paginator::useBootstrap();

        Blade::if('super_admin', function () {
            return auth()->user()->isSuperAdmin();
        });

        Blade::if('admin_sport', function () {
            return auth()->user()->isAdminSport();
        });

        Blade::if('admin_org', function () {
            return auth()->user()->isAdminOrg();
        });
        
        Blade::if('coach', function () {
            return auth()->user()->isCoach();
        });

        Blade::if('adviser', function () {
            return auth()->user()->isAdviser();
        });

        Blade::if('student', function () {
            return auth()->user()->isStudent();
        });
    }
}
