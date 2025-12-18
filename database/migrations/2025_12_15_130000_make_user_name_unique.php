<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix duplicate names if any: keep the first, modify others by appending a short random suffix
        $duplicateNames = DB::table('users')
            ->select('name', DB::raw('COUNT(*) as cnt'))
            ->groupBy('name')
            ->having('cnt', '>', 1)
            ->pluck('name');

        foreach ($duplicateNames as $name) {
            $users = DB::table('users')->where('name', $name)->orderBy('id')->get();
            $skipFirst = true;
            foreach ($users as $user) {
                if ($skipFirst) { $skipFirst = false; continue; }
                $newName = $user->name . '-' . Str::random(6);
                // Ensure newName is unique (very unlikely collision)
                while (DB::table('users')->where('name', $newName)->exists()) {
                    $newName = $user->name . '-' . Str::random(6);
                }
                DB::table('users')->where('id', $user->id)->update(['name' => $newName]);
            }
        }

        // Add unique index on name
        if (! Schema::hasColumn('users', 'name')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
    }
};
