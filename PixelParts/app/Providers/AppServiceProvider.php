<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;

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
        // Compartilhar categorias com todas as views
        View::composer('*', function ($view) {
            $categorias = Category::orderBy('name', 'asc')->get();
            $view->with('categorias', $categorias);
        });
    }
}
