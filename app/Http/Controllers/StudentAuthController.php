<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;

class StudentAuthController extends Controller
{
    public function showLogin() {
        return view('student.login');
    }

    public function login(Request $request) 
{
    // 1. تعريف مفتاح التقييد بناءً على IP الطالب ورقم الهوية لزيادة الأمان
    $key = 'student_login_' . $request->identity_number . '|' . $request->ip();

    // 2. التحقق مما إذا كان الطالب قد تجاوز عدد المحاولات (5 محاولات)
    if (RateLimiter::tooManyAttempts($key, 5)) {
        $seconds = RateLimiter::availableIn($key);
        return back()->with('error', "محاولات كثيرة خاطئة. يرجى الانتظار $seconds ثانية قبل المحاولة مجدداً.");
    }

    // 3. التحقق من صحة البيانات المدخلة
    $request->validate([
        'identity_number' => 'required',
        'password'        => 'required'
    ]);

    // 4. البحث عن الطالب مع بيانات مدرسته
    $student = Student::with('school')->where('identity_number', $request->identity_number)->first();

    // 5. التحقق من وجود الطالب وصحة كلمة المرور
    if ($student && Hash::check($request->password, $student->password)) {
        
        // أ. فحص حالة المدرسة (معطلة أو مؤرشفة)
        $school = $student->school;
        if (!$school || $school->is_active == false || $school->is_archived == true) {
            return back()->with('error', 'عذراً، لا يمكنك الدخول لأن حساب المدرسة معطل حالياً.');
        }

        // ب. فحص حالة الطالب (يجب أن يكون "يدرس")
        if ($student->status !== 'يدرس') {
            $msg = "عذراً، لا يمكنك الدخول. حالتك الحالية هي: (" . $student->status . "). بوابة الطالب تفتح فقط لمن هم في مرحلة الدراسة.";
            if ($student->status === 'ناجح') {
                $msg = "تهانينا بنجاحك! لقد أتممت التدريب وبوابتك مغلقة الآن.";
            }
            return back()->with('error', $msg);
        }

        // ج. نجاح الدخول: مسح سجل المحاولات وتوليد الجلسة
        RateLimiter::clear($key);
        $request->session()->regenerate();
        session(['student_id' => $student->id, 'student_name' => $student->name]);
        
        return redirect()->route('student.dashboard');
    }

    // 6. في حال فشل الدخول: تسجيل محاولة خاطئة وتفعيل التقييد
    RateLimiter::hit($key, 60); // الحظر لمدة 60 ثانية بعد تجاوز الحد
    
    return back()->with('error', 'رقم الهوية أو كلمة المرور غير صحيحة');
}

    public function logout(Request $request) {
        session()->forget(['student_id', 'student_name']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // ✅ هذا يُفعِّل رسالة "تم تسجيل خروجك" في الـ View
    return redirect()->route('student.login')
                     ->with('status', 'logged-out');
    }


public function postLogin(Request $request)
{
    $key = 'student_login_' . $request->ip();

    // ✅ تحقق من الـ Rate Limit أولاً
    if (RateLimiter::tooManyAttempts($key, 5)) {
        $seconds = RateLimiter::availableIn($key);
        return back()
            ->withInput($request->only('identity_number'))
            ->with('lockout_seconds', $seconds)
            ->with('login_attempts', 5);
    }

    // التحقق من البيانات
    $credentials = $request->validate([
        'identity_number' => 'required|string',
        'password'        => 'required|string',
    ]);

    $student = Student::where('identity_number', $credentials['identity_number'])->first();

    if (!$student || !Hash::check($credentials['password'], $student->password)) {
        // ✅ سجّل محاولة فاشلة
        RateLimiter::hit($key, 60); // قفل لمدة 60 ثانية بعد 5 محاولات

        $attempts = RateLimiter::attempts($key);

        return back()
            ->withInput($request->only('identity_number'))
            ->with('error', 'رقم الهوية أو كلمة المرور غير صحيحة')
            ->with('login_attempts', $attempts);
    }

    // ✅ نجاح — نظّف المحاولات وسجّل الدخول
    RateLimiter::clear($key);
    Auth::guard('student')->login($student);

    return redirect()->route('student.dashboard');
}

}