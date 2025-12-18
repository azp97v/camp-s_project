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
        // populate missing identifiers
        $users = DB::table('users')->whereNull('identifier')->get(['id']);

        foreach ($users as $u) {
            DB::table('users')->where('id', $u->id)->update([
                'identifier' => (string) Str::uuid(),
            ]);
        }

        // add unique index
        Schema::table('users', function (Blueprint $table) {
            $table->unique('identifier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['identifier']);
        });
    }
};
