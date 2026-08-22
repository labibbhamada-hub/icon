<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    public function verify(EmailVerificationRequest $request)
    {
        if (!$request->user()->hasVerifiedEmail()) {
            $request->fulfill();

            return view('auth.email-verified', [
                'alreadyVerified' => false,
            ]);
        }

        return view('auth.email-verified', [
            'alreadyVerified' => true,
        ]);
    }

    public function send(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return back()->with(
                'success',
                'Your email address has already been verified.'
            );
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with(
            'success',
            'A new verification link has been sent to your email address.'
        );
    }
}
