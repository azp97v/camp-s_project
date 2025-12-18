<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
/**
 * Run the migrations.
 */
public function up(): void
{
    Schema::create('goals', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('title');
        $table->text('description')->nullable();
        // المدة الأساسية للهدف بالثواني
        $table->unsignedBigInteger('total_duration_seconds')->default(0);
        // المدة المتبقية التي سنخصم منها بعد إنهاء الجلسات
        $table->unsignedBigInteger('remaining_duration_seconds')->default(0);
        $table->timestamps();
    });
}

/**
 * Reverse the migrations.
 */
public function down(): void
{
    Schema::dropIfExists('goals');
}


};



