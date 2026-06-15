<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy("order","asc")->get();
        return view('dashboard.faq.index', compact('faqs'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|max:255',
            'answer' => 'required|max:500',
        ]);

        $orderList = (Faq::max('order') ?? 0) + 1;
        
        $faq = Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'order' => $orderList,
        ]);

        if ($faq) {
            return redirect()->route('admin.faq.index')->with('success', 'Added successfully');
        } else {
            return redirect()->route('admin.faq.index')->with('error', 'Failed to add Faq');
        }
    }

    public function edit($id)
    {
        $faq = Faq::find($id);
        return view('dashboard.faq.edit', compact('faq'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|max:255',
            'answer' => 'required|max:500',
        ]);

        $faq = Faq::findOrFail($id);

        $faq = $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        if ($faq) {
            return redirect()->route('admin.faq.index')->with('success', 'Updated successfully');
        } else {
            return redirect()->route('admin.faq.index')->with('error', 'Failed to update Faq');
        }        
    }

    public function reorder(Request $request)
    {
        // dd($request->order);

        foreach ($request->order as $index => $id) {
            $faq = Faq::find($id);
            $faq->order = $index;
            $faq->save();
        }

        return response()->json(['status' => 'success']);
    }

    public function destroy($id)
    {
        $faq = Faq::find($id);
        $faq->delete();
        return redirect()->route('admin.faq.index')->with('success', 'Deleted successfully');
    }
}
