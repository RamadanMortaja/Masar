<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('school_code')->unique(); 
            $table->string('email')->nullable(); // بريد التواصل للمدرسة
            $table->string('phone')->nullable(); // هاتف التواصل
            $table->string('logo')->nullable(); 
            $table->enum('plan', ['monthly', 'yearly']); 
            $table->integer('student_limit')->default(150); 
            $table->date('subscription_end'); 
            $table->boolean('is_active')->default(true); 
            $table->boolean('is_archived')->default(false); // للأرشفة
            $table->softDeletes(); // تفعيل الحذف المنطقي (Soft Delete)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};