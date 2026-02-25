<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
    'question_text', // أضفنا الـ underscore
    'answer_1',
    'answer_2',
    'answer_3',
    'answer_4',
    'correct_answer',
    'license_type',
    'theory_type',
    'exam_type',
    'sign_id',
    'question_explanation',
    'question_picture'
];
}