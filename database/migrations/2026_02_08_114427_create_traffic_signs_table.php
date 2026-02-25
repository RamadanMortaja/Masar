<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('traffic_signs', function (Blueprint $table) {
            $table->id(); // هذا الحقل لـ Dexie وللارافيل
            $table->string('title'); // TrafficSignTitle
            $table->text('description')->nullable(); // TrafficSignDescription
            $table->string('category'); // warning, priority, etc (سنحتاجه للفلترة)
            $table->string('code'); // TrafficSignSignNumber
            $table->string('image')->nullable(); // مسار الصورة SVG
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('traffic_signs');
    }
};