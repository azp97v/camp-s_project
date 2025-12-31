<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * EmailVerificationNotificationController
     * --------------------------------------------------------
     * Arabic: يرسل رابط التحقق بالبريد الإلكتروني عندما يطلب المستخدم ذلك.
     * English: Sends an email verification notification if the user isn't verified.
     * No behavior changes; comments only.
     */
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
