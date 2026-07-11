<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutPageController extends Controller
{
    public function index()
    {
        $aboutPage = (object) AboutUs::pluck('key_value', 'key_name')->toArray();
        $imageId = $aboutPage->image ?? null;
        $image = Storage::url(optional(Media::find($imageId, ['*']))->src ?? asset('default.webp'));
        return view('dashboard.about-page.index', compact('aboutPage', 'image'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'top_header' => 'required',
            'top_sub_header' => 'required',
            'heading' => 'required',
            'description' => 'required',
            'media_id' => 'nullable|numeric',
            'button_text' => 'required',
            'button_link' => 'required',
            'mission_header' => 'required|string|max:100',
            'our_mission' => 'required|string|max:150',
            'vision_header' => 'required|string|max:100',
            'our_vision' => 'required|string|max:150',
            'values_header' => 'required|string|max:100',
            'our_values' => 'required|string|max:150',
        ]);

        $inputs = $request->except(['_token', '_method']);

        if (!empty($inputs['media_id'])) {
            $inputs['image'] = $inputs['media_id'];
        } else {
            unset($inputs['image']);
        }

        unset($inputs['media_id']);
        try {
            foreach ($inputs as $key => $value) {
                AboutUs::updateOrCreate(
                    ['key_name' => $key],
                    ['key_value' => $value]
                );
            }
            return redirect()->route('admin.about-page.index')->with('success', 'Updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
