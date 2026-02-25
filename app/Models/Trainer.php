<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trainer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id','name','phone','identity_number',
        'license_number','license_expiry','specialization',
        'status','image','notes',
    ];

    public function school()    { return $this->belongsTo(School::class); }
    public function students()  { return $this->hasMany(Student::class, 'trainer_name', 'name'); }
    public function sessions()  { return $this->hasMany(DrivingSession::class); }

    public function isLicenseExpiringSoon(): bool
    {
        if (!$this->license_expiry) return false;
        return \Carbon\Carbon::parse($this->license_expiry)->diffInDays(now(), false) >= -30;
    }
}
