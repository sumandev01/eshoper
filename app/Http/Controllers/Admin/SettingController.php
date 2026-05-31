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
        dd($request->all());
        // Update setting logic here
    }
}
