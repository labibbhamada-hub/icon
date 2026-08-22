<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'password' => [
                'required',
                'string',
            ],
        ]);

        $email = Str::lower(
            $credentials['email']
        );

        $throttleKey =
            $email . '|' . $request->ip();

        if (
            RateLimiter::tooManyAttempts(
                $throttleKey,
                5
            )
        ) {
            $seconds =
                RateLimiter::availableIn(
                    $throttleKey
                );

            return back()
                ->withInput(
                    $request->only('email')
                )
                ->withErrors([
                    'email' =>
                    "Too many login attempts. Please try again in {$seconds} seconds."
                ]);
        }

        if (!Auth::attempt([
            'email' => $email,
            'password' => $credentials['password'],
            'status' => 'active',
        ], $request->boolean('remember'))) {

            RateLimiter::hit(
                $throttleKey,
                60
            );

            return back()
                ->withInput(
                    $request->only('email')
                )
                ->withErrors([
                    'email' => 'Email or password is incorrect.',
                ]);
        }

        RateLimiter::clear(
            $throttleKey
        );

        $request->session()->regenerate();

        return $this->redirectByRole();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view('auth.register');
    }

    public function register(
        RegisterRequest $request
    ) {
        $data = $request->validated();

        $user = User::create([
            'name' =>
            $data['name'],

            'email' =>
            Str::lower(
                $data['email']
            ),

            'password' =>
            Hash::make(
                $data['password']
            ),

            'role' =>
            'participant',

            'status' =>
            'active',
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        $user->sendEmailVerificationNotification();

        return redirect()
            ->route('verification.notice');
    }

    private function redirectByRole()
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin' =>
            redirect()->route(
                'admin.dashboard'
            ),

            'reviewer' =>
            redirect()->route(
                'reviewer.dashboard'
            ),

            'participant' =>
            redirect()->route(
                'participant.dashboard'
            ),

            default =>
            $this->logoutWithError(
                'Your account does not have a valid portal.'
            ),
        };
    }

    private function logoutWithError(
        string $message
    ) {
        Auth::logout();

        return redirect()
            ->route('login')
            ->withErrors([
                'email' => $message,
            ]);
    }
}
