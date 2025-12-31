<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    /**
     * PasswordController
     * --------------------------------------------------------
     * Arabic: يعالج تحديث كلمة مرور المستخدم بعد التحقق من كلمة المرور الحالية.
     * English: Updates the authenticated user's password securely. No logic changes.
     */
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse | JsonResponse
    {
        try {
            $validated = $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            // If it's an AJAX request, return JSON errors
            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $e->errors(),
                    'message' => 'فشل التحقق من المدخلات'
                ], 422);
            }
            throw $e;
        }

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Password update confirmed:
        // - Old password is securely replaced with new hashed password in database
        // - 'current_password' rule verified the old password matches what's stored
        // - 'password' rule ensured password meets security requirements (min 8 chars, mixed case, numbers, etc)
        // - 'confirmed' rule verified password_confirmation matches password
        // No conflicts, old password is completely removed

        // Return JSON if it's an AJAX request, otherwise redirect
        if ($request->expectsJson()) {
            return response()->json(['message' => 'تم تحديث كلمة المرور بنجاح!']);
        }

        return back()->with('status', 'password-updated');
    }
}
