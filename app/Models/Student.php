<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $fillable = [
        'name', 'identity_number', 'password', 'school_id', 'image', 'birth_date', 
        'phone', 'gender', 'address', 'license_type', 'gear_type', 'status', 
        'trainer_name', 'car_info', 'total_lessons', 'start_date', 'end_date',
        'medical_test_date', 'medical_test_result', 'theory_type', 'payment_type', 
        'total_amount', 'paid_amount', 'last_payment_date', 'theory_exam_date', 
        'theory_exam_result', 'practical_test_date', 'practical_test_examiner', 
        'practical_test_result', 'notes', 'is_active','medical_test_expiry','theory_exam_expiry'
        ,'last_activity_at','unresolved_errors_count'
    ];

    protected $hidden = ['password'];

    // علاقة المدرسة
    public function school() {
        return $this->belongsTo(School::class);
    }

    // حساب المبلغ المتبقي تلقائياً
    public function getRemainingAmountAttribute() {
        return $this->total_amount - $this->paid_amount;
    }

    public function payments() {
    return $this->hasMany(\App\Models\Payment::class);
}
public function sessions() {
    return $this->hasMany(\App\Models\DrivingSession::class);
}
}