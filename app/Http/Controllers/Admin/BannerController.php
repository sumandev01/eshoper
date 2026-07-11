<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $offer1 = Banner::firstOrCreate(['position' => 1], ['title' => '', 'status' => 1]);
        $offer2 = Banner::firstOrCreate(['position' => 2], ['title' => '', 'status' => 1]);

        return view('dashboard.banners.index', compact('offer1', 'offer2'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'offer1_title' => 'nullable|string',
            'offer1_subtitle' => 'nullable|string',
            'offer1_image' => 'nullable',
            'offer1_image_id' => 'nullable',
            'offer1_type' => 'nullable|string',
            'offer1_ref_id' => 'nullable|string',
            'offer1_status' => 'required|boolean',

            'offer2_title' => 'nullable|string',
            'offer2_subtitle' => 'nullable|string',
            'offer2_image' => 'nullable',
            'offer2_image_id' => 'nullable',
            'offer2_type' => 'nullable|string',
            'offer2_ref_id' => 'nullable|string',
            'offer2_status' => 'required|boolean',
        ]);

        // Workaround for media-picker bug that uses target_id as input name
        $offer1ImageId = $request->offer1_image_id ?? $request->offer1_image;
        $offer2ImageId = $request->offer2_image_id ?? $request->offer2_image;

        // Update Offer 1
        Banner::updateOrCreate(
            ['position' => 1],
            [
                'title' => $request->offer1_title,
                'subtitle' => $request->offer1_subtitle,
                'image_id' => $offer1ImageId,
                'link_type' => $request->offer1_type,
                'link_ref_id' => $request->offer1_ref_id,
                'status' => $request->offer1_status,
            ]
        );

        // Update Offer 2
        Banner::updateOrCreate(
            ['position' => 2],
            [
                'title' => $request->offer2_title,
                'subtitle' => $request->offer2_subtitle,
                'image_id' => $offer2ImageId,
                'link_type' => $request->offer2_type,
                'link_ref_id' => $request->offer2_ref_id,
                'status' => $request->offer2_status,
            ]
        );

        return redirect()->back()->with('success', 'Banner offers updated successfully.');
    }
}
