<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SizeRequest;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::latest('id')->get();
        return view('dashboard.size.index', compact('sizes'));
    }
    public function store(SizeRequest $request)
    {
        $size = Size::create([
            'name' => $request->name
        ]);
        if ($size) {
            return redirect()->route('size.index')->with('success', 'Size created successfully');
        }else {
            return redirect()->back()->with('error', 'Failed to create size');
        }
    }

    public function edit(Size $size)
    {
        return view('dashboard.size.edit', compact('size'));
    }

    public function update(SizeRequest $request, Size $size)
    {
        $size->update([
            'name' => $request->name
        ]);
        if ($size) {
            return redirect()->route('size.index')->with('success', 'Size updated successfully');
        }else {
            return redirect()->back()->with('error', 'Failed to update size');
        }
    }

    public function destroy(Size $size)
    {
        $size->delete();
        return redirect()->route('size.index')->with('success', 'Size deleted successfully');
    }
}
