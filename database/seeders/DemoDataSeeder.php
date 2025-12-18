<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Goal;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $user = User::first();

            if (! $user) {
                $user = User::create([
                    'name' => 'Demo User',
                    'email' => 'demo@example.com',
                    'password' => Hash::make('password'),
                ]);
            }

            $goal1 = Goal::create([
                'user_id' => $user->id,
                'title' => 'تعلم البرمجة',
                'description' => 'هدف تجريبي لتجربة واجهة الأهداف والمهام',
                'total_duration_seconds' => 3600 * 10,
                'remaining_duration_seconds' => 3600 * 8,
            ]);

            $goal2 = Goal::create([
                'user_id' => $user->id,
                'title' => 'مشروع جانبي',
                'description' => 'مهمة تجريبية ثانية',
                'total_duration_seconds' => 3600 * 5,
                'remaining_duration_seconds' => 3600 * 5,
            ]);

            $goal1->tasks()->create([
                'title' => 'قراءة كتاب عن الخوارزميات',
                'description' => 'قراءة 50 صفحة يومياً',
                'deadline' => now()->addDays(7),
                'status' => 'idle',
                'last_session_seconds' => 0,
                'total_tracked_seconds' => 0,
            ]);

            $goal1->tasks()->create([
                'title' => 'حل تمارين',
                'description' => 'حل 10 مسائل من موقع تعليمي',
                'deadline' => now()->addDays(14),
                'status' => 'idle',
                'last_session_seconds' => 0,
                'total_tracked_seconds' => 0,
            ]);

            $goal2->tasks()->create([
                'title' => 'بدء إعداد المستودع',
                'description' => 'تهيئة repo وكتابة README',
                'deadline' => now()->addDays(3),
                'status' => 'idle',
                'last_session_seconds' => 0,
                'total_tracked_seconds' => 0,
            ]);
        });
    }
}
