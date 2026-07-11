<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Enums\Permission\AdminAccessEnums;

class SocialLoginController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                if ($user->hasPermissionTo(AdminAccessEnums::AdminAccess->value)) {
                    return redirect()->route('login')->with('error', 'Admin/Staff accounts cannot be accessed via Social Login.');
                }
                
                // Update provider info if user already exists
                $user->update([
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                ]);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $socialUser->getName() ?? 'User',
                    'email' => $socialUser->getEmail(),
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'role' => 'user',
                    'password' => bcrypt(\Illuminate\Support\Str::random(24)) // Needs a random password for model validation
                ]);
            }

            Auth::login($user);

            return redirect()->route('user.dashboard');
            
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Authentication failed. Please try again.');
        }
    }
}
