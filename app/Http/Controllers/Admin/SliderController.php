<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('order')->get();
        return view('dashboard.sliders.index', compact('sliders'));
    }

    public function add()
    {
        return view('dashboard.sliders.add');
    }

    public function store(Request $request)
    {
        if ($request->has('main_thumb')) {
            $request->merge([
                'media_id' => $request->input('main_thumb'),
            ]);
        }
        $request->validate([
            'title' => 'required|string|max:40|unique:sliders,title',
            'subtitle' => 'required|string|max:50|unique:sliders,subtitle',
            'button_text' => 'required|string|max:20',
            'button_link' => 'required|max:255',
            'media_id' => 'required|exists:media,id',
        ]);

        $OrderList = (Slider::max('order') ?? 0) + 1;

        Slider::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'media_id' => $request->media_id,
            'status' => 1,
            'order' => $OrderList,
        ]);

        return redirect()->route('slider.index')->with('success', 'Slider created successfully.');
    }

    public function edit($id)
    {
        $slider = Slider::findOrFail($id);
        return view('dashboard.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)  // Type hint দাও
    {
        if ($request->has('main_thumb')) {
            $request->merge([
                'media_id' => $request->input('main_thumb'),
            ]);
        }

        $request->validate([
            'title'       => 'required|string|max:40|unique:sliders,title,' . $slider->id,
            'subtitle'    => 'required|string|max:50|unique:sliders,subtitle,' . $slider->id,
            'button_text' => 'required|string|max:20',
            'button_link' => 'required|max:255',
            'media_id'    => 'required|exists:media,id',
            'is_active'   => 'required|boolean',
        ]);

        $slider->update([
            'title'       => $request->title,
            'subtitle'    => $request->subtitle,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'media_id'    => $request->media_id,
            'is_active'   => $request->is_active,
        ]);

        return redirect()->route('slider.index')->with('success', 'Slider updated successfully.');
    }

    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        $slider->delete();
        return redirect()->route('slider.index')->with('success', 'Slider deleted successfully.');
    }

    public function reorder(Request $request)
    {
        // Reorder the sliders based on the provided order
        foreach ($request->order as $index => $id) {
            Slider::where('id', $id)->update(['order' => $index + 1]);
        }
        return response()->json(['status' => 'success']);
    }
}
