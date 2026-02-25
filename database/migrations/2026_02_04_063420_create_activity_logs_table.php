<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // من قام بالعملية
            $table->string('action'); // نوع العملية: إضافة، تعديل، أرشفة.. الخ
            $table->string('model_type'); // اسم الموديل (غالباً School)
            $table->unsignedBigInteger('model_id'); // رقم المدرسة المتأثرة
            $table->text('description'); // وصف تفصيلي (مثلاً: قام بتعديل اسم المدرسة من أ إلى ب)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};