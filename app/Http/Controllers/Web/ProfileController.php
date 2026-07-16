<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = auth('web')->user();
        $userProfileImage = $user->profile_image ? asset('storage/' . $user->profile_image) : asset('user.webp');
        return view('web.dashboard.profile', compact('user', 'userProfileImage'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth('web')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'is_subscribed' => $request->has('is_subscribed'),
        ];

        if ($request->hasFile('avatar')) {
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $path = $request->file('avatar')->store('users', 'public');
            $data['profile_image'] = $path;
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function deactivateAccount(Request $request)
    {
        $user = auth('web')->user();
        $user->update(['status' => 'inactive']);
        auth('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('error', 'Your account has been deactivated.');
    }
}
