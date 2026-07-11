<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    public function index()
    {
        $couriers = Courier::latest()->paginate(10);
        return view('dashboard.courier.index', compact('couriers'));
    }

    public function create()
    {
        return view('dashboard.courier.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tracking_url' => 'nullable|url|max:255',
            'status' => 'required|boolean',
        ]);

        Courier::create($request->all());

        return redirect()->route('admin.couriers.index')->with('success', 'Courier created successfully.');
    }

    public function edit(Courier $courier)
    {
        return view('dashboard.courier.edit', compact('courier'));
    }

    public function update(Request $request, Courier $courier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tracking_url' => 'nullable|url|max:255',
            'status' => 'required|boolean',
        ]);

        $courier->update($request->all());

        return redirect()->route('admin.couriers.index')->with('success', 'Courier updated successfully.');
    }

    public function destroy(Courier $courier)
    {
        $courier->delete();
        return redirect()->route('admin.couriers.index')->with('success', 'Courier deleted successfully.');
    }
}
