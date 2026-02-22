<?php

namespace App\Providers;

use App\Models\Wishlist;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        // pagination view for Bootstrap 5
        Paginator::useBootstrapFive();

        View::composer('*', function ($view) {
            $wishlistIds = auth('web')->check()
                ? Wishlist::where('user_id', auth('web')->id())
                ->pluck('product_id')
                ->toArray()
                : [];

            $view->with('wishlistIds', $wishlistIds);
        });
    }
}
