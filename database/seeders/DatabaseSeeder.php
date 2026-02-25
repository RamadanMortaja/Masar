<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\School;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. إنشاء حساب الأدمن العام (أبو فوزي)
        User::create([
            'name' => 'أبو فوزي - مدير النظام',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);

    // إنشاء مدرسة ببيانات مطابقة تماماً للجداول اللي عندك
$school = School::create([
    'name'             => 'مدرسة الأمل لتعليم السياقة',
    'school_code'      => 'HOPE-01',
    'plan'             => 'الخطة الذهبية',
    'student_limit'    => 100,
    'subscription_end' => now()->addYear(),
    // حذفنا 'status' و 'phone' و 'address' لأنهم سببوا أخطاء عدم وجود أعمدة
]);
        // 3. إنشاء حساب مدير لهذه المدرسة
        User::create([
            'name' => 'مدير مدرسة الأمل',
            'email' => 'school@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'school_admin',
            'school_id' => $school->id,
        ]);
    }
}