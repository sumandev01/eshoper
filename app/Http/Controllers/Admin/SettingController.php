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
        $logoId = Setting::whereKeyName('site_logo')->value('key_value');
        $faviconId = Setting::whereKeyName('site_favicon')->value('key_value');
        $siteLogo = Storage::url(optional(Media::find($logoId, ['*']))->src ?? asset('default.webp'));
        $siteFavicon = Storage::url(optional(Media::find($faviconId, ['*']))->src ?? asset('default.webp'));

        return view('dashboard.settings.index', compact('siteLogo', 'siteFavicon'));
    }

    public function update(Request $request)
    {
        $inputs = $request->validate([
            'site_title' => 'required',
            'site_logo' => 'nullable|exists:media,id',
            'logo' => 'nullable',
            'site_favicon' => 'nullable|exists:media,id',
            'favicon' => 'nullable',
            'site_description' => 'required|max:1500',
            'site_keywords' => 'required|max:255',
            'contact_email' => 'required|email',
            'contact_phone' => 'required',
            'contact_address' => 'required|max:255',
            'footer_text' => 'required|max:255',
            'facebook_pixel' => 'nullable',
            'google_analytics' => 'nullable',
            'currency_symbol' => 'required',
            'currency_code' => 'required',
            'google_map' => 'nullable',
            'theme_color_primary' => 'required|string',
            'theme_color_primary_hover' => 'required|string',
            'theme_color_secondary' => 'required|string',
            'theme_color_dark' => 'required|string',
            'theme_button_bg' => 'required|string',
            'theme_button_text' => 'required|string',
            'theme_button_hover_bg' => 'required|string',
            'theme_button_hover_text' => 'required|string',
        ]);

        if (! empty($inputs['logo'])) {
            $inputs['site_logo'] = $inputs['logo'];
        } else {
            unset($inputs['site_logo']);
        }
        if (! empty($inputs['favicon'])) {
            $inputs['site_favicon'] = $inputs['favicon'];
        } else {
            unset($inputs['site_favicon']);
        }

        unset($inputs['logo']);
        unset($inputs['favicon']);

        try {
            foreach ($inputs as $key => $value) {
                Setting::updateOrCreate(
                    ['key_name' => $key],
                    ['key_value' => $value]
                );
            }

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
}
