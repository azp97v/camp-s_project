<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * ProfileController
 * --------------------------------------------------------
 * Arabic: هذا الكنترولر يعرض نموذج الملف الشخصي للمستخدم، ويعالج
 * تحديث المعلومات وحذف الحساب. يستخدم Blade لعرض الواجهات.
 * English: Handles viewing/updating/deleting the authenticated user's profile.
 * Updated to use Blade views instead of Inertia.
 */
class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        try {
            $request->validate([
                'password' => ['required', 'current_password'],
            ]);

            $user = $request->user();

            Auth::logout();

            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // If AJAX request, return JSON
            if ($request->expectsJson()) {
                return response()->json(['message' => 'تم حذف حسابك بنجاح'], 200);
            }

            return Redirect::to('/');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If AJAX request, return JSON error
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'كلمة المرور غير صحيحة',
                    'errors' => $e->errors()
                ], 422);
            }

            // Otherwise redirect back with errors
            return Redirect::back()->withErrors($e->errors());
        }
    }
}
