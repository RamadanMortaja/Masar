<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;


class AuthController extends Controller
{
    // عرض صفحة الدخول
    public function showLogin() {
        return view('auth.login');
    }

    // معالجة عملية الدخول
    public function login(Request $request)
{
    // 1. مفتاح التقييد بناءً على البريد الإلكتروني و IP لزيادة الأمان
    $throttleKey = 'login_' . strtolower($request->email) . '|' . $request->ip();

    // 2. التحقق من تجاوز عدد المحاولات (5 محاولات)
    if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
        $seconds = RateLimiter::availableIn($throttleKey);
        return back()
            ->withInput($request->only('email'))
            ->with('lockout_seconds', $seconds)
            ->withErrors(['email' => "تم حظر الدخول مؤقتاً. يرجى المحاولة بعد $seconds ثانية."]);
    }

    // 3. التحقق من صحة البيانات المدخلة
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    // 4. محاولة تسجيل الدخول
    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $user = Auth::user();

        // --- الفحص الذكي للحالة (المنطق الذي أضفته أنت) ---
        if ($user->role === 'school_admin') {
            $school = $user->school;

            // فحص هل المدرسة معطلة أو مؤرشفة
            if (!$school || $school->is_active == false || $school->is_archived == true) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'عذراً، حساب المدرسة حالياً معطل أو مؤرشف. يرجى التواصل مع الإدارة العامة.',
                ]);
            }
        }

        // 5. نجاح الدخول: تنظيف سجل المحاولات وتجديد الجلسة
        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        // التوجيه حسب الصلاحية
        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        } elseif ($user->role === 'school_admin') {
            return redirect()->intended(route('school.dashboard'));
        }

        return redirect()->intended('/');
    }

    // 6. فشل الدخول: تسجيل محاولة خاطئة
    RateLimiter::hit($throttleKey, 60); // الحظر لمدة دقيقة بعد تجاوز الحد
    $attempts = RateLimiter::attempts($throttleKey);

    return back()
        ->withInput($request->only('email'))
        ->withErrors(['email' => 'بيانات الدخول المدخلة غير صحيحة.'])
        ->with('login_attempts', $attempts);
}

    // تسجيل الخروج
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        // هذا السطر يُفعِّل رسالة "تم تسجيل خروجك" في الـ View
        return redirect()->route('login')
                         ->with('status', 'logged-out');
    }
}
