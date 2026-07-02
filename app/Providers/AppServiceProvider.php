<?php

namespace App\Providers;

use App\Models\Media;
use App\Models\Setting;
use App\Models\Wishlist;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
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
                ? Wishlist::whereUserId(auth('web')->user()->id)
                ->pluck('product_id')
                ->toArray()
                : [];

            $view->with('wishlistIds', $wishlistIds);
        });

        try {
            if (!app()->runningInConsole() && Schema::hasTable('settings')) {
                $siteSettings = (object) [
                    ...Setting::pluck('key_value', 'key_name')->toArray()
                ];

                $logoId = $siteSettings->site_logo ?? null;
                $logo = asset('default.webp');
                if (is_numeric($logoId)) {
                    $media = Media::find($logoId);
                    $logo = $media ? Storage::url($media->src) : asset('default.webp');
                } elseif ($logoId) {
                    $logo = asset($logoId);
                }

                $faviconId = $siteSettings->site_favicon ?? null;
                $favicon = asset('default.webp');
                if (is_numeric($faviconId)) {
                    $media = Media::find($faviconId);
                    $favicon = $media ? Storage::url($media->src) : asset('default.webp');
                } elseif ($faviconId) {
                    $favicon = asset($faviconId);
                }

                $siteSettings->site_logo = $logo;
                $siteSettings->site_favicon = $favicon;

                View::share('siteSettings', $siteSettings);
            }
        } catch (\Exception $e) {
            if (app()->runningInConsole()) {
                throw $e;
            }
        }
    }
}
