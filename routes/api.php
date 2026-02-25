<?php

use App\Models\Question;
use App\Models\Student;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentAuthController;
use App\Http\Controllers\Api\QuestionApiController;
use App\Http\Controllers\StudentController;

Route::post('/login', [StudentAuthController::class, 'login']);

//Route::post('/api/student/save-exam', [StudentController::class, 'saveExamResult'])->name('student.exam.save');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/questions/sync', [QuestionApiController::class, 'getAllQuestions']);
});