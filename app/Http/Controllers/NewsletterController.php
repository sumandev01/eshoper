<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletters,email'
        ], [
            'email.unique' => 'You are already subscribed to our newsletter!'
        ]);

        Newsletter::create([
            'email' => $request->email
        ]);

        return redirect()->back()->with('success', 'Successfully subscribed to the newsletter!');
    }

    public function adminIndex()
    {
        $newsletters = Newsletter::latest()->paginate(20);
        return view('dashboard.newsletters.index', compact('newsletters'));
    }

    public function destroy($id)
    {
        $newsletter = Newsletter::findOrFail($id);
        $newsletter->delete();
        return redirect()->back()->with('success', 'Subscriber deleted successfully.');
    }
}
