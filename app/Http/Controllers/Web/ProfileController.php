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
        $userProfileImage = Storage::url(optional(Media::find($user->media_id, ['*']))->src ?? asset('default.webp'));
        return view('web.dashboard.profile', compact('user', 'userProfileImage'));
    }
}
