<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('school_admin'); // العمود هنا يُنشأ لأول مرة
            $table->unsignedBigInteger('school_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // احذف أي كود آخر هنا يتعلق بـ Password Resets أو Sessions إذا كان يسبب زحمة
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};