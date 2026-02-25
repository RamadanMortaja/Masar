<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('students', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('identity_number')->unique();
        $table->string('password');
        $table->foreignId('school_id')->constrained()->onDelete('cascade');
        $table->string('image')->nullable();
        
        // بيانات شخصية
        $table->date('birth_date')->nullable();
        $table->string('phone')->nullable();
        $table->enum('gender', ['ذكر', 'أنثى'])->nullable();
        $table->string('address')->nullable();
        
        // بيانات الحالة والتدريب
        $table->string('license_type')->nullable();
        $table->string('gear_type')->default('manual');
        $table->string('status')->default('يدرس');
        $table->string('trainer_name')->nullable();
        $table->string('car_info')->nullable();
        $table->integer('total_lessons')->default(0);
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
        
        // بيانات طبية
        $table->date('medical_test_date')->nullable();
        $table->date('medical_test_expiry')->nullable(); // تأكد أنه هنا فقط
        $table->string('medical_test_result')->nullable();
        $table->string('theory_type')->nullable(); 

        // بيانات الامتحان (تأكد من عدم التكرار هنا)
        $table->date('theory_exam_date')->nullable(); // مرة واحدة فقط
        $table->date('theory_exam_expiry')->nullable();
        $table->string('theory_exam_result')->nullable();
        
        // بيانات مالية
        $table->string('payment_type')->nullable();
        $table->decimal('total_amount', 10, 2)->default(0);
        $table->decimal('paid_amount', 10, 2)->default(0);
        $table->date('last_payment_date')->nullable();
        
        // امتحانات عملية
        $table->date('practical_test_date')->nullable();
        $table->string('practical_test_examiner')->nullable();
        $table->string('practical_test_result')->nullable();
        
        $table->text('notes')->nullable();
        $table->boolean('is_active')->default(true);
        $table->softDeletes();
        $table->timestamps();

        $table->timestamp('last_activity_at')->nullable(); 
        $table->integer('unresolved_errors_count')->default(0); 
    });
}
};
