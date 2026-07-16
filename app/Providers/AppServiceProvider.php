<?php

namespace App\Providers;

use App\Enums\RoleEnums;
use App\Models\Media;
use App\Models\Wishlist;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
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
        // Super Admin Bypass Gate
        Gate::before(function ($user, $ability) {
            if ($user->hasRole(RoleEnums::Super_Admin->value)) {
                return true;
            }
        });

        // pagination view for Bootstrap 5
        Paginator::useBootstrapFive();

        View::composer(['web.components.product_card'], function ($view) {
            static $wishlistIds = null;
            if ($wishlistIds === null) {
                $wishlistIds = auth('web')->check()
                    ? Wishlist::whereUserId(auth('web')->user()->id)
                        ->pluck('product_id')
                        ->toArray()
                    : [];
            }

            $view->with('wishlistIds', $wishlistIds);
        });

        try {
            if (! app()->runningInConsole() && Schema::hasTable('settings')) {
                // Initialize default settings (this triggers auto-creation in DB via get_setting)
                $defaults = [
                    'site_title' => 'MartX',
                    'site_logo' => null,
                    'site_mobile_logo' => null,
                    'site_favicon' => null,
                    'site_description' => 'Best e-commerce platform',
                    'site_keywords' => 'ecommerce, shop, online store',
                    'social_facebook' => '',
                    'social_twitter' => '',
                    'social_linkedin' => '',
                    'social_instagram' => '',
                    'social_youtube' => '',
                    'theme_color_primary' => '#D19C97',
                    'theme_color_dark' => '#1C1C1C',
                    'theme_button_bg' => '#D19C97',
                    'theme_button_text' => '#111111',
                    'payment_stripe_status' => '0',
                    'payment_sslcommerz_status' => '0',
                    'payment_cod_status' => '0',
                    'payment_manual_status' => '0',
                    'payment_manual_instruction' => '',
                    'mail_host' => 'smtp.gmail.com',
                    'mail_port' => '465',
                    'mail_username' => '',
                    'mail_password' => '',
                    'mail_encryption' => 'ssl',
                    'mail_from_address' => 'noreply@eshopper.com',
                ];

                foreach ($defaults as $key => $default) {
                    get_setting($key, $default);
                }

                // Professionally override the global App Name
                $siteTitle = get_setting('site_title');
                if (!empty($siteTitle)) {
                    config(['app.name' => $siteTitle]);
                }
                
                // Override Mail Config
                if (!empty(get_setting('mail_host'))) {
                    config([
                        'mail.default' => 'smtp',
                        'mail.mailers.smtp.host' => get_setting('mail_host'),
                        'mail.mailers.smtp.port' => get_setting('mail_port'),
                        'mail.mailers.smtp.username' => get_setting('mail_username'),
                        'mail.mailers.smtp.password' => get_setting('mail_password'),
                        'mail.mailers.smtp.encryption' => strtolower(get_setting('mail_encryption')),
                        'mail.from.address' => get_setting('mail_from_address'),
                        'mail.from.name' => get_setting('site_title', config('app.name')),
                    ]);
                }

                $siteSettings = new \SiteSettingsProxy;

                $logoId = get_setting('site_logo');
                $logo = asset('default.webp');
                if (is_numeric($logoId)) {
                    $media = Media::find($logoId);
                    $logo = $media ? Storage::url($media->src) : asset('default.webp');
                } elseif ($logoId) {
                    $logo = asset($logoId);
                }

                $mobileLogoId = get_setting('site_mobile_logo');
                $mobileLogo = null;
                if (is_numeric($mobileLogoId)) {
                    $media = Media::find($mobileLogoId);
                    if ($media) {
                        $mobileLogo = Storage::url($media->src);
                    }
                } elseif ($mobileLogoId) {
                    $mobileLogo = asset($mobileLogoId);
                }

                $faviconId = get_setting('site_favicon');
                $favicon = asset('default.webp');
                if (is_numeric($faviconId)) {
                    $media = Media::find($faviconId);
                    $favicon = $media ? Storage::url($media->src) : asset('default.webp');
                } elseif ($faviconId) {
                    $favicon = asset($faviconId);
                }

                $siteSettings->site_logo = $logo;
                $siteSettings->site_mobile_logo = $mobileLogo;
                $siteSettings->site_favicon = $favicon;

                View::share('siteSettings', $siteSettings);
            }

            if (! app()->runningInConsole() && Schema::hasTable('payment_methods')) {
                View::share('paymentMethods', \App\Models\PaymentMethod::orderBy('order')->get());
            }
        } catch (\Exception $e) {
            if (app()->runningInConsole()) {
                throw $e;
            }
        }
    }
}
