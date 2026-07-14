<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $logoId = Setting::whereKeyName('site_logo')->value('key_value');
        $mobileLogoId = Setting::whereKeyName('site_mobile_logo')->value('key_value');
        $faviconId = Setting::whereKeyName('site_favicon')->value('key_value');
        $logoImage = Storage::url(Media::find($logoId)->src);
        $mobileLogoImage = Storage::url(Media::find($mobileLogoId)->src);
        $faviconImage = Storage::url(Media::find($faviconId)->src);

        return view('dashboard.settings.index', compact('logoId', 'mobileLogoId', 'faviconId', 'logoImage', 'mobileLogoImage', 'faviconImage'));
    }

    public function update(Request $request)
    {
        $inputs = $request->validate([
            'site_title' => 'required',
            'seo_title' => 'nullable|max:255',
            'site_logo' => 'nullable|exists:media,id',
            'logo' => 'nullable',
            'site_mobile_logo' => 'nullable|exists:media,id',
            'mobile_logo' => 'nullable',
            'site_favicon' => 'nullable|exists:media,id',
            'favicon' => 'nullable',
            'site_description' => 'required|max:1500',
            'site_keywords' => 'required|max:255',
            'contact_email' => 'required|email',
            'contact_phone' => 'required',
            'contact_address' => 'required|max:255',
            'footer_text' => 'required|max:255',
            'home_trending_title' => 'nullable|max:100',
            'home_latest_title' => 'nullable|max:100',
            'subscribe_heading' => 'nullable|max:100',
            'subscribe_text' => 'nullable|max:500',
            'facebook_pixel' => 'nullable',
            'google_analytics' => 'nullable',
            'currency_symbol' => 'required',
            'currency_code' => 'required',
            'google_map' => 'nullable',
            'social_facebook' => 'nullable|string',
            'social_twitter' => 'nullable|string',
            'social_linkedin' => 'nullable|string',
            'social_instagram' => 'nullable|string',
            'social_youtube' => 'nullable|string',
            'theme_color_primary' => 'required|string',
            'theme_color_dark' => 'nullable|string',
            'theme_button_bg' => 'required|string',
            'theme_button_text' => 'nullable|string',
        ]);

        if (! empty($inputs['logo'])) {
            $inputs['site_logo'] = $inputs['logo'];
        } else {
            unset($inputs['site_logo']);
        }

        if (! empty($inputs['mobile_logo'])) {
            $inputs['site_mobile_logo'] = $inputs['mobile_logo'];
        } else {
            unset($inputs['site_mobile_logo']);
        }

        if (! empty($inputs['favicon'])) {
            $inputs['site_favicon'] = $inputs['favicon'];
        } else {
            unset($inputs['site_favicon']);
        }

        unset($inputs['logo']);
        unset($inputs['mobile_logo']);
        unset($inputs['favicon']);

        try {
            $allInputs = $request->except(['_token', '_method', 'logo', 'mobile_logo', 'favicon']);
            $allInputs = array_merge($allInputs, $inputs);

            foreach ($allInputs as $key => $value) {
                Setting::updateOrCreate(
                    ['key_name' => $key],
                    ['key_value' => $value]
                );
            }

            Cache::forget('site_settings');

            return redirect()->route('admin.settings.index')->with('success', 'Updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update settings.');
        }
    }

    public function paymentGateways()
    {
        return view('dashboard.settings.payment-gateways');
    }

    public function updatePaymentGateways(Request $request)
    {
        $inputs = $request->validate([
            'payment_stripe_status' => 'nullable|in:0,1',
            'payment_sslcommerz_status' => 'nullable|in:0,1',
            'payment_cod_status' => 'nullable|in:0,1',
            'payment_manual_status' => 'nullable|in:0,1',
            'payment_manual_instruction' => 'nullable|string',
        ]);

        $settingsToUpdate = [
            'payment_stripe_status' => $inputs['payment_stripe_status'] ?? '0',
            'payment_sslcommerz_status' => $inputs['payment_sslcommerz_status'] ?? '0',
            'payment_cod_status' => $inputs['payment_cod_status'] ?? '0',
            'payment_manual_status' => $inputs['payment_manual_status'] ?? '0',
            'payment_manual_instruction' => $inputs['payment_manual_instruction'] ?? '',
        ];

        try {
            foreach ($settingsToUpdate as $key => $value) {
                Setting::updateOrCreate(
                    ['key_name' => $key],
                    ['key_value' => $value]
                );
            }

            \Illuminate\Support\Facades\Cache::forget('payment_gateways');
            \Illuminate\Support\Facades\Cache::forget('site_settings');

            return redirect()->back()->with('success', 'Payment gateways updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update payment gateways.');
        }
    }
}
