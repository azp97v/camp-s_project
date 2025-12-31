<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailOtp;
use App\Mail\SendOtpCode;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * RegisteredUserController
     * --------------------------------------------------------
     * Arabic: يعرض نموذج التسجيل ويعالج إنشاء حساب مستخدم جديد.
     * English: Shows registration form and handles creating a new User.
     * No behavior changes — validation and creation logic kept intact.
     */
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create OTP and keep registration data in session until verification
        $otp = Str::upper(Str::random(6));
        $expires = Carbon::now()->addMinutes(3);

        EmailOtp::create([
            'email' => $request->email,
            'otp' => $otp,
            'expires_at' => $expires,
            'attempts' => 0,
        ]);

        try {
            Mail::to($request->email)->send(new SendOtpCode($request->name, $otp));
        } catch (\Exception $e) {
            Log::error('Registration OTP send failed: ' . $e->getMessage());
        }

        // Store registration data temporarily in session (not yet created)
        session(['registration.pending' => [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]]);

        // Store email in session (not in URL for privacy)
        session(['otp_email' => $request->email]);

        // Help local testing: expose last OTP in session when debugging
        if (config('app.debug')) {
            session(['last_otp' => $otp]);
        }

        return redirect()->route('otp.verify');
    }
}
