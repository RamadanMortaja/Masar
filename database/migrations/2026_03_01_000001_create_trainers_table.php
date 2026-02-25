<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('identity_number')->nullable();
            $table->string('license_number')->nullable();
            $table->date('license_expiry')->nullable();
            $table->string('specialization')->default('ملاكي');
            $table->string('status')->default('نشط');
            $table->string('image')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void { Schema::dropIfExists('trainers'); }
};
