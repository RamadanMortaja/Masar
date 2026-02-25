<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrivingSession extends Model
{
    protected $fillable = [
        'school_id','student_id','trainer_id','vehicle_id',
        'session_date','start_time','end_time',
        'duration_minutes','status','location','rating','notes',
    ];

    public function school()  { return $this->belongsTo(School::class); }
    public function student() { return $this->belongsTo(Student::class); }
    public function trainer() { return $this->belongsTo(Trainer::class); }
    public function vehicle() { return $this->belongsTo(Vehicle::class); }
}
