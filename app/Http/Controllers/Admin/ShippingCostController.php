<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingCost;
use App\Models\State;
use Illuminate\Http\Request;

class ShippingCostController extends Controller
{
    public function index()
    {
        $shippingCosts = ShippingCost::with('states')->get();
        return view('dashboard.shipping_cost.index', compact('shippingCosts'));
    }

    public function create()
    {
        $states = State::with('country')->where('status', 1)->whereNull('shipping_cost_id')->get();
        return view('dashboard.shipping_cost.add', compact('states'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'state_ids' => 'required|array',
            'state_ids.*' => 'exists:states,id'
        ]);

        $shippingCost = ShippingCost::create([
            'location' => $request->location,
            'price' => $request->price,
        ]);

        // Assign states to this shipping cost
        State::whereIn('id', $request->state_ids)->update(['shipping_cost_id' => $shippingCost->id]);

        return redirect()->route('admin.shipping_cost.index')->with('success', 'Shipping Cost created successfully.');
    }

    public function edit(ShippingCost $shippingCost)
    {
        $states = State::with('country')->where('status', 1)->where(function($q) use ($shippingCost) {
            $q->whereNull('shipping_cost_id')
              ->orWhere('shipping_cost_id', $shippingCost->id);
        })->get();
        $selectedStates = $shippingCost->states->pluck('id')->toArray();
        return view('dashboard.shipping_cost.edit', compact('shippingCost', 'states', 'selectedStates'));
    }

    public function update(Request $request, ShippingCost $shippingCost)
    {
        $request->validate([
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'state_ids' => 'required|array',
            'state_ids.*' => 'exists:states,id'
        ]);

        $shippingCost->update([
            'location' => $request->location,
            'price' => $request->price,
        ]);

        // Unassign old states
        State::where('shipping_cost_id', $shippingCost->id)->update(['shipping_cost_id' => null]);
        
        // Assign new states
        State::whereIn('id', $request->state_ids)->update(['shipping_cost_id' => $shippingCost->id]);

        return redirect()->route('admin.shipping_cost.index')->with('success', 'Shipping Cost updated successfully.');
    }

    public function destroy(ShippingCost $shippingCost)
    {
        // Unassign states
        State::where('shipping_cost_id', $shippingCost->id)->update(['shipping_cost_id' => null]);
        
        $shippingCost->delete();
        return back()->with('success', 'Shipping Cost deleted successfully.');
    }
}
