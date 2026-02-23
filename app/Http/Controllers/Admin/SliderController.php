<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::latest()->get();
        return view('dashboard.sliders.index', compact('sliders'));
    }

    public function add()
    {
        return view('dashboard.sliders.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'button_text' => 'required|string|max:255',
            'button_link' => 'required|url|max:255',
            'media_id' => 'required',
        ]);
        // Validate and store the slider
    }

    public function edit($id)
    {
        // Retrieve the slider by ID and return the edit view
        return view('dashboard.sliders.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Validate and update the slider
    }

    public function destroy($id)
    {
        // Delete the slider by ID
    }
}
