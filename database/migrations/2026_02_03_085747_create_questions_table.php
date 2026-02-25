<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
    $table->id();
    $table->text('question_text'); // تأكد من وجود الـ underscore
    $table->text('answer_1');
    $table->text('answer_2');
    $table->text('answer_3')->nullable();
    $table->text('answer_4')->nullable();
    $table->integer('correct_answer');
    $table->string('license_type');
    $table->string('theory_type');
    $table->string('exam_type');
    $table->string('sign_id')->nullable();
    $table->text('question_explanation')->nullable();
    $table->string('question_picture')->nullable();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};