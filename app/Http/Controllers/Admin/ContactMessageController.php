<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $contacts = ContactMessage::latest('id')->get();
        return view('dashboard.contact-message.index', compact('contacts'));
    }

    public function view(Request $request)
    {
        $contact = ContactMessage::findOrFail($request->id);
        $contact->status = 1;
        $contact->save();
        return response()->json([
            'status' => true,
        ]);
    }
}
