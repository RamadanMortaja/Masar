<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id','plate_number','brand','model','year',
        'color','gear_type','vehicle_type','status',
        'insurance_expiry','license_expiry',
        'last_maintenance','next_maintenance',
        'km_reading','notes',
    ];

    public function school()   { return $this->belongsTo(School::class); }
    public function sessions() { return $this->hasMany(DrivingSession::class); }

    public function getDisplayNameAttribute(): string
    {
        return trim("{$this->brand} {$this->model} ({$this->plate_number})");
    }

    public function isInsuranceExpiringSoon(): bool
    {
        if (!$this->insurance_expiry) return false;
        return \Carbon\Carbon::parse($this->insurance_expiry)->diffInDays(now(), false) >= -14;
    }

    public function isMaintenanceDue(): bool
    {
        if (!$this->next_maintenance) return false;
        return \Carbon\Carbon::parse($this->next_maintenance)->isPast();
    }
}
