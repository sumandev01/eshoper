<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $logoId = get_setting('site_logo');
        $mobileLogoId = get_setting('site_mobile_logo');
        $faviconId = get_setting('site_favicon');
        
        $siteLogo = Storage::url(optional(Media::find($logoId, ['*']))->src ?? asset('default.webp'));
        
        $siteMobileLogo = null;
        if($mobileLogoId){
            $siteMobileLogo = Storage::url(optional(Media::find($mobileLogoId, ['*']))->src ?? asset('default.webp'));
        }
        
        $siteFavicon = Storage::url(optional(Media::find($faviconId, ['*']))->src ?? asset('default.webp'));

        return view('dashboard.settings.index', compact('siteLogo', 'siteMobileLogo', 'siteFavicon'));
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
            
            \Illuminate\Support\Facades\Cache::forget('site_settings');

            return redirect()->route('admin.settings.index')->with('success', 'Updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update settings.');
        }
    }

    public function offers()
    {
        $offer1Id = Setting::whereKeyName('offer1_image')->value('key_value');
        $offer2Id = Setting::whereKeyName('offer2_image')->value('key_value');

        $offer1Image = asset('default.webp');
        if (is_numeric($offer1Id)) {
            $media = Media::find($offer1Id);
            $offer1Image = $media ? Storage::url($media->src) : asset('default.webp');
        } elseif ($offer1Id) {
            $offer1Image = asset($offer1Id);
        }

        $offer2Image = asset('default.webp');
        if (is_numeric($offer2Id)) {
            $media = Media::find($offer2Id);
            $offer2Image = $media ? Storage::url($media->src) : asset('default.webp');
        } elseif ($offer2Id) {
            $offer2Image = asset($offer2Id);
        }

        return view('dashboard.offers.index', compact('offer1Image', 'offer2Image'));
    }

    public function updateOffers(Request $request)
    {
        $inputs = $request->validate([
            'offer1_status' => 'nullable',
            'offer1_title' => 'required',
            'offer1_subtitle' => 'nullable',
            'offer1_link' => 'nullable',
            'offer1_image' => 'nullable',
            'offer1_image_id' => 'nullable',

            'offer2_status' => 'nullable',
            'offer2_title' => 'required',
            'offer2_subtitle' => 'nullable',
            'offer2_link' => 'nullable',
            'offer2_image' => 'nullable',
            'offer2_image_id' => 'nullable',
        ]);

        if (!empty($inputs['offer1_image_id'])) {
            $inputs['offer1_image'] = $inputs['offer1_image_id'];
        } else {
            unset($inputs['offer1_image']);
        }

        if (!empty($inputs['offer2_image_id'])) {
            $inputs['offer2_image'] = $inputs['offer2_image_id'];
        } else {
            unset($inputs['offer2_image']);
        }

        unset($inputs['offer1_image_id']);
        unset($inputs['offer2_image_id']);

        try {
            foreach ($inputs as $key => $value) {
                Setting::updateOrCreate(
                    ['key_name' => $key],
                    ['key_value' => $value]
                );
            }

            return redirect()->back()->with('success', 'Offers updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update offers.');
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

            return redirect()->back()->with('success', 'Payment gateways updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update payment gateways.');
        }
    }
}
