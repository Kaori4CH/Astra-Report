<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function loginview(): View
    {
        return view('auth.login', ['title' => 'Astra Report - Login']);
    }

    public function loginPost(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(Auth::user()->homeUrl());
        }

        return back()->withInput($request->only('email'))->withErrors([
            'email' => 'Email atau password tidak cocok.',
        ]);
    }

    public function registerview()
    {
        return view('auth.register', [
            'title' => 'Astra Report - Daftar Dealer',
            'dealers' => Dealer::orderBy('name')->get(),
        ]);
    }


    public function registerPost(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string',],
            'email' => ['required', 'string', 'email', 'unique:users,email'],
            'dealer_id' => ['required', ],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'dealer',
            'dealer_id' => $validated['dealer_id'],
        ]);

        return redirect()->route('login-view')->with('success', 'Akun berhasil dibuat, silakan login.');
    }

    public function logoutPost(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login-view');
    }
}
