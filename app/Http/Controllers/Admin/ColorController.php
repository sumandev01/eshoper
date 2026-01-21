<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ColorRequest;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::latest('id')->get();
        return view('dashboard.color.index', compact('colors'));
    }

    public function store(ColorRequest $request)
    {   
        $color = Color::create([
            'name' => $request->name,
            'color_code' => $request->color_code
        ]);
        if ($color) {
            return redirect()->route('color.index')->with('success', 'Color created successfully');
        }else {
            return redirect()->back()->with('error', 'Failed to create color');
        }
    }

    public function edit(Color $color)
    {
        return view('dashboard.color.edit', compact('color'));
    }

    public function update(ColorRequest $request, Color $color)
    {
        $color->update([
            'name' => $request->name,
            'color_code' => $request->color_code
        ]);
        if ($color) {
            return redirect()->route('color.index')->with('success', 'Color updated successfully');
        }else {
            return redirect()->back()->with('error', 'Failed to update color');
        }
    }

    public function destroy(Color $color)
    {
        $color->delete();
        return redirect()->route('color.index')->with('success', 'Color deleted successfully');
    }
}
