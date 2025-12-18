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
    Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('goal_id')->constrained()->cascadeOnDelete();
        $table->string('title');
        $table->text('description')->nullable();
        $table->dateTime('deadline')->nullable();

// Removed duplicate lines that are outside the closure

        // حالة المؤقت والمهمة
        // idle: لا يوجد جلسة نشطة
        // running: المؤقت يعمل
        // stopped: تم إيقاف المؤقت ومتاح للإنهاء أو الإلغاء
        $table->enum('status', ['idle','running','stopped'])->default('idle');

        // وقت بداية الجلسة الحالية (إن وُجدت)
        $table->dateTime('timer_started_at')->nullable();

        // مدة آخر جلسة موقوفة بالثواني (قبل الإنهاء أو الإلغاء)
        $table->unsignedBigInteger('last_session_seconds')->default(0);

        // إجمالي الزمن المتتبع لهذه المهمة (تراكمي)
        $table->unsignedBigInteger('total_tracked_seconds')->default(0);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
