<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\Media;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function contact()
    {
        return view('web.pages.contact');
    }

    public function contactRequest(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email',
            'phone' => 'required|phone:AUTO,INTERNATIONAL',
            'subject' => 'required|string|max:100',
            'message' => 'required|string|max:1000',
        ], [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'phone.required' => 'Phone is required.',
            'phone.phone' => 'Invalid phone number.',
            'subject.required' => 'Subject is required.',
            'message.required' => 'Message is required.',
        ]);

        $userIp = $request->ip();

        try {
            $contact = ContactMessage::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
                'status' => 0,
                'ip_address' => $userIp,
            ]);

            return redirect()->route('contact')->with('success', 'Sent successfully.');
        } catch (\Exception $e) {
            return redirect()->route('contact')->with('error', 'Please try again.');
        }
    }

    public function about()
    {
        $aboutPages = (object)AboutUs::pluck('key_value', 'key_name')->toArray();
        $mediaId = $aboutPages->image ?? null;
        $media = null;
        if (!empty($mediaId)) {
            $media = Storage::url(optional(Media::find($mediaId, ['*']))->src);
        } else {
            $media = asset('about.jpg');
        }
        $teamMembers = TeamMember::with('media')->orderBy('order', 'asc')->where('is_active', 1)->get();
        return view('web.pages.about', compact('aboutPages', 'media', 'teamMembers'));
    }

    // Dynamic Page
    public function page($slug)
    {
        $page = \App\Models\Page::where('slug', $slug)->where('status', 1)->firstOrFail();
        return view('web.pages.page', compact('page'));
    }

    // Faq
    public function faq()
    {
        $faqs = Faq::orderBy('order', 'asc')->get();
        return view('web.pages.faq', compact('faqs'));
    }
}
