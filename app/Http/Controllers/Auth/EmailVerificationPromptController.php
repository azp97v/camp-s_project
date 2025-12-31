<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmailVerificationPromptController extends Controller
{
    /**
     * EmailVerificationPromptController
     * --------------------------------------------------------
     * Arabic: يعرض مطالبة التحقق من البريد الإلكتروني إذا لم يكن مفعلًا.
     * English: Shows the email verification prompt (or redirects if already verified).
     * Implementation unchanged; only descriptive comments added.
     */
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|Response
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('dashboard', absolute: false))
                    : Inertia::render('Auth/VerifyEmail', ['status' => session('status')]);
    }
}
