<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('plate_number');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->smallInteger('year')->nullable();
            $table->string('color')->nullable();
            $table->string('gear_type')->default('manual');
            $table->string('vehicle_type')->default('ملاكي');
            $table->string('status')->default('نشط');
            $table->date('insurance_expiry')->nullable();
            $table->date('license_expiry')->nullable();
            $table->date('last_maintenance')->nullable();
            $table->date('next_maintenance')->nullable();
            $table->decimal('km_reading', 10, 0)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void { Schema::dropIfExists('vehicles'); }
};
