<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. جدول الأخطاء (إذا لم يكن موجوداً)
        if (!Schema::hasTable('student_errors')) {
            Schema::create('student_errors', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
                $table->integer('question_id');
                $table->integer('error_count')->default(1);
                $table->timestamps();
            });
        }

        // 2. جدول نتائج الامتحانات
        if (!Schema::hasTable('exam_results')) {
            Schema::create('exam_results', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
                $table->integer('score');
                $table->string('status');
                $table->timestamp('exam_date')->useCurrent();
                $table->timestamps();
            });
        }

        // 3. جدول التقدم (السؤال تم مشاهدته أم لا)
        if (!Schema::hasTable('student_question_progress')) {
            Schema::create('student_question_progress', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
                $table->integer('question_id');
                $table->integer('is_viewed')->default(1);
                $table->timestamps();
                $table->unique(['student_id', 'question_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('student_question_progress');
        Schema::dropIfExists('exam_results');
        Schema::dropIfExists('student_errors');
    }
};