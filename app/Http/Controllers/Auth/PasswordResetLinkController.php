<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    /**
     * PasswordResetLinkController
     * --------------------------------------------------------
     * Arabic: يعرض نموذج طلب رابط إعادة تعيين كلمة المرور ويعالج إرساله.
     * English: Shows the forgot-password view and sends password reset links.
     * No behavior changes; only added explanatory comments.
     */
    /**
     * Display the password reset link request view.
     */
    public function create()
    {
        // Render Blade view for forgot-password so users see a native page
        return view('auth.forgot-password', [
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Send password reset link and return appropriate response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
