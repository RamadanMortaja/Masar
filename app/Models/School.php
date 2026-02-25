<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'school_code',
        'email',
        'phone',
        'logo',
        'plan',
        'student_limit',
        'subscription_end',
        'is_active',
        'is_archived',
    ];

    // علاقة الطلاب
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    // علاقة المدير
    public function manager()
    {
        return $this->hasOne(User::class)->where('role', 'school_admin');
    }

    // ===== علاقات جديدة =====

    public function trainers()
    {
        return $this->hasMany(Trainer::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function sessions()
    {
        return $this->hasMany(DrivingSession::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function subscriptionPayments()
{
    return $this->hasMany(\App\Models\SubscriptionPayment::class);
}
}
