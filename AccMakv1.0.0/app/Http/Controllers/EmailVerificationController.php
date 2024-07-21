<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function show()
    {
        return view('account.emailverification');
    }

    public function verify(EmailVerificationRequest $request)
    {
        if($request->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')->withErrors('Your email has already been verified.');
        }
        
        $request->fulfill();

        return redirect()->route('verification.notice')->with('email_verified', 'Your email has been verified. You can now preform actions that require email verification.');
    }

    public function resend(Request $request)
    {
        if($request->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')->withErrors('A verification link can\'t be resent as your email has already been verified.');
        }

        $request->user()->sendEmailVerificationNotification();

        return redirect()->route('verification.notice')->with('resend_verification', 'A verification link has been sent to your email address <b>'.htmlspecialchars($request->user()->email).'</b>. Verify your email to perform actions that require email verification.');
    }
}