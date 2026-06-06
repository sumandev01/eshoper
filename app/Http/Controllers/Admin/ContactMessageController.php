<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index()
    {
        $contacts = ContactMessage::latest('id')->get();
        return view('dashboard.contact-message.index', compact('contacts'));
    }
}
