<?php

namespace App\Http\Controllers\Auth;

use App\Enums\AuthEnums;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    // public function loginRequest(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|min:8'
    //     ]);

    //     if (Auth::attempt($request->only('email', 'password'))) {
    //         $user = Auth::user();

    //         if ($user->hasRole(AuthEnums::USER->value)) {
    //             return redirect()->route('root')->with('success', 'Logged in successfully');
    //         }

    //         Auth::logout();
    //         return redirect()->route('login')->with('error', 'Do not have permission');
    //     }

    //     return redirect()->route('login')->with('error', 'Email or password is incorrect');
    // }

    public function loginRequest(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:8'
    ]);

    if (Auth::attempt($request->only('email', 'password'))) {
        $user = Auth::user();

        if ($user->hasRole(AuthEnums::USER->value)) {
            // যদি AJAX রিকোয়েস্ট হয় (মোডাল থেকে আসলে)
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logged in successfully'
                ], 200);
            }

            return redirect()->route('root')->with('success', 'Logged in successfully');
        }

        Auth::logout();

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Do not have permission'
            ], 403);
        }

        return redirect()->route('login')->with('error', 'Do not have permission');
    }

    // লগইন ফেইল হলে
    if ($request->ajax()) {
        return response()->json([
            'success' => false,
            'message' => 'Email or password is incorrect'
        ], 401);
    }

    return redirect()->route('login')->with('error', 'Email or password is incorrect');
}

    public function register()
    {
        return view('auth.register');
    }

    public function registerRequest(AuthRequest $request, AuthRepository $authRepository)
    {
        $user = $authRepository->createUser($request);
        $user->assignRole(AuthEnums::USER);

        Auth::login($user);
        
        return redirect()->route('root')->with('success', 'User registered successfully');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('root')->with('success', 'logged out successfully');
    }
}
