<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $navCategories = Category::query()
                ->active()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(['id', 'name', 'slug']);

            $view->with('navCategories', $navCategories);
        });
    }
}
