<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered as RegisteredEvent;
use App\Mail\SendOtpCode;
use Illuminate\Support\Facades\Log;

class EmailOtpController extends Controller
{
    public function showVerify(Request $request)
    {
        $email = $request->query('email') ?: session('otp_email');
        if ($email) {
            session(['otp_email' => $email]);
        }
        // Compute remaining validity and resend cooldown
        $remainingValidity = 0;
        $secondsUntilResend = 0;
        // Resend blocked until previous OTP expires — use OTP's remaining validity
        $remainingValidity = 0;
        $secondsUntilResend = 0;

        if ($email) {
            $record = EmailOtp::where('email', $email)->latest()->first();
            if ($record) {
                $now = Carbon::now();
                $remainingValidity = max(0, $record->expires_at->timestamp - $now->timestamp);
                // block resend until expiry
                $secondsUntilResend = $remainingValidity;
            }
        }

        return view('auth.otp-verify', [
            'email' => $email,
            'remainingValidity' => $remainingValidity,
            'secondsUntilResend' => $secondsUntilResend,
            'resendCooldown' => $secondsUntilResend,
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string',
        ]);

        $record = EmailOtp::where('email', $request->email)->latest()->first();
        if (! $record) {
            return back()->withErrors(['otp' => 'رمز التحقق غير صحيح أو منتهي الصلاحية']);
        }

        if (Carbon::now()->greaterThan($record->expires_at)) {
            return back()->withErrors(['otp' => 'رمز التحقق منتهي الصلاحية']);
        }

        if (! hash_equals($record->otp, $request->otp)) {
            $record->increment('attempts');
            return back()->withErrors(['otp' => 'رمز التحقق غير صحيح']);
        }

        // Verified — delete record
        $record->delete();

        // If there's a pending registration in session, create the user now
        $pending = session('registration.pending');
        if ($pending && isset($pending['email']) && $pending['email'] === $request->email) {
            $user = User::create([
                'name' => $pending['name'],
                'email' => $pending['email'],
                'password' => $pending['password'],
            ]);

            event(new RegisteredEvent($user));
            Auth::login($user);
            session()->forget('registration.pending');

            return view('auth.otp-success');
        }

        return view('auth.otp-success');
    }

    public function resend(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Check last sent OTP for this email: block until previous OTP expires
        $last = EmailOtp::where('email', $request->email)->latest()->first();
        $now = Carbon::now();
        if ($last) {
            $remainingValidity = max(0, $last->expires_at->timestamp - $now->timestamp);
            // allow resend if remainingValidity is 0 or 1 second to account for small clock drift
            if ($remainingValidity > 1) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'status' => 'already_sent',
                        'secondsUntilResend' => (int) $remainingValidity,
                        'remainingValidity' => (int) $remainingValidity,
                    ], 429);
                }

                return redirect()->route('otp.verify')->with('status', 'تم إرسال رمز بالفعل. يرجى الانتظار حتى تنتهي صلاحية الرمز قبل طلب رمز جديد.');
            }
        }

        $otp = Str::upper(Str::random(6));
        $expires = Carbon::now()->addMinutes(3);

        EmailOtp::create([
            'email' => $request->email,
            'otp' => $otp,
            'expires_at' => $expires,
            'attempts' => 0,
        ]);

        // Send OTP via Mailable
        try {
            $user = User::where('email', $request->email)->first();
            $name = $user?->name ?? 'المستخدم';
            Mail::to($request->email)->send(new SendOtpCode($name, $otp));
        } catch (\Exception $e) {
            Log::error('OTP email send failed: ' . $e->getMessage());
        }

        // For local/dev troubleshooting: keep last OTP in session when app debug
        if (config('app.debug')) {
            session(['last_otp' => $otp]);
        }

        session(['otp_email' => $request->email]);
        // Prepare timings for response — block until this OTP expires
        $remainingValidity = max(0, $expires->timestamp - Carbon::now()->timestamp);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'sent',
                'secondsUntilResend' => (int) $remainingValidity,
                'remainingValidity' => (int) $remainingValidity,
            ]);
        }

        return redirect()->route('otp.verify')->with('status', 'تم إرسال رمز التحقق');
    }
}
