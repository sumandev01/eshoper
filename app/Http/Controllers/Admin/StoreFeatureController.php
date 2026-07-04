<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreFeature;
use Illuminate\Http\Request;

class StoreFeatureController extends Controller
{
    public function index()
    {
        $storeFeatures = StoreFeature::orderBy('order')->get();
        return view('dashboard.store_features.index', compact('storeFeatures'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255'
        ]);

        $maxOrder = StoreFeature::max('order') ?? 0;

        StoreFeature::create([
            'title' => $request->title,
            'icon' => $request->icon,
            'order' => $maxOrder + 1
        ]);

        return redirect()->route('admin.store-features.index')->with('success', 'Feature added successfully.');
    }

    public function edit(StoreFeature $storeFeature)
    {
        return view('dashboard.store_features.edit', compact('storeFeature'));
    }

    public function update(Request $request, StoreFeature $storeFeature)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255'
        ]);

        $storeFeature->update([
            'title' => $request->title,
            'icon' => $request->icon
        ]);

        return redirect()->route('admin.store-features.index')->with('success', 'Feature updated successfully.');
    }

    public function destroy(StoreFeature $storeFeature)
    {
        $storeFeature->delete();
        return redirect()->back()->with('success', 'Feature deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'exists:store_features,id'
        ]);

        foreach ($request->order as $index => $id) {
            StoreFeature::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
