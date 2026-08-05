<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'Email atau password salah.',
                ]);
        }
        $request->session()->regenerate();
        // Cek user aktif
        if (Auth::user()->status != 'active') {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Akun anda tidak aktif.',
            ]);
        }
        // Redirect berdasarkan role
        switch (Auth::user()->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'reviewer':
                return redirect('/reviewer/dashboard');
            case 'participant':
                return redirect('/participant/dashboard');
            default:
                Auth::logout();
                abort(403);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
