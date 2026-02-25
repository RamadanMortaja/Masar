<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    protected $table = 'exam_results';
    protected $fillable = ['student_id', 'score', 'total', 'status', 'answers', 'exam_type'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
