<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('receipt_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->default('نقدي');
            $table->date('payment_date');
            $table->string('description')->nullable();
            $table->string('received_by')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('payments'); }
};
