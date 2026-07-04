<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::orderBy('order')->get();
        return view('dashboard.payment_methods.index', compact('paymentMethods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'media_id' => 'required|exists:media,id'
        ]);

        $maxOrder = PaymentMethod::max('order') ?? 0;

        PaymentMethod::create([
            'name' => $request->name,
            'media_id' => $request->media_id,
            'order' => $maxOrder + 1
        ]);

        return redirect()->route('admin.payment-methods.index')->with('success', 'Payment Method added successfully.');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('dashboard.payment_methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'media_id' => 'required|exists:media,id'
        ]);

        if($main_thumb = $request->input('main_thumb')) {
            $request->merge([
                'media_id' => $main_thumb,
            ]);
        }

        $paymentMethod->update([
            'name' => $request->name,
            'media_id' => $request->media_id
        ]);

        return redirect()->route('admin.payment-methods.index')->with('success', 'Payment Method updated successfully.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        return redirect()->back()->with('success', 'Payment Method deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'exists:payment_methods,id'
        ]);

        foreach ($request->order as $index => $id) {
            PaymentMethod::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
