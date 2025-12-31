<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * AuthController
     * --------------------------------------------------------
     * Arabic: بسيط لإدارة عرض نماذج تسجيل الدخول والتسجيل ومعالجة المصادقة.
     * English: Lightweight auth helper for rendering login/register forms and handling login/logout.
     * Note: The project uses Fortify/Jetstream in places; this controller provides
     * legacy routes/views compatibility. No behavior changes applied.
     */
    /**
     * Display the login form
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard')->with('success', 'تم تسجيل الدخول بنجاح');
        }

        return back()->withErrors([
            'email' => 'بيانات الدخول غير صحيحة'
        ])->onlyInput('email');
    }

    /**
     * Display the registration form
     */
    public function showRegisterForm()
    {
        return view('register');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'تم تسجيل الخروج بنجاح');
    }
}
