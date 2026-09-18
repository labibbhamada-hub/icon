<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function verify(Request $request, $id, $hash)
    {
        $user = \App\Models\User::findOrFail($id);

        if (!hash_equals(
            sha1($user->getEmailForVerification()),
            (string) $hash
        )) {
            abort(403, 'Invalid verification link.');
        }

        $alreadyVerified = $user->hasVerifiedEmail();

        if (!$alreadyVerified) {
            $user->markEmailAsVerified();
        }

        return view('auth.email-verified', [
            'alreadyVerified' => $alreadyVerified,
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
