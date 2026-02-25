<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required', // سنبقي اسم المدخل username في الطلب لكن سنبحث به في identity_number
            'password' => 'required',
        ]);

        // البحث باستخدام العمود الصحيح identity_number
        $student = Student::where('identity_number', $request->username)->first();

        if (!$student || !Hash::check($request->password, $student->password)) {
            return response()->json(['message' => 'بيانات الدخول غير صحيحة'], 401);
        }

        $token = $student->createToken('student_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'identity_number' => $student->identity_number
            ]
        ]);
    }
}