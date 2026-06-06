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
        $request->validate([
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
        ]);

        $inputs = $request->except(['_token', '_method']);

        if (!empty($inputs['logo'])) {
            $inputs['site_logo'] = $inputs['logo'];
        } else {
            unset($inputs['site_logo']);
        }
        if (!empty($inputs['favicon'])) {
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
}
