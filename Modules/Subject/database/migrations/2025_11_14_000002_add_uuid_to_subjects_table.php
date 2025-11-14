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
        if (Schema::hasTable('subjects')) {
            Schema::table('subjects', function (Blueprint $table) {
                // Add UUID column if it doesn't exist
                if (!Schema::hasColumn('subjects', 'uuid')) {
                    $table->uuid('uuid')->nullable()->unique()->after('id');
                }
            });

            // Populate UUID for existing subjects that don't have one
            $subjects = DB::table('subjects')->whereNull('uuid')->get();
            foreach ($subjects as $subject) {
                DB::table('subjects')
                    ->where('id', $subject->id)
                    ->update(['uuid' => (string) Str::uuid()]);
            }

            // Make UUID not nullable after populating
            Schema::table('subjects', function (Blueprint $table) {
                $table->uuid('uuid')->nullable(false)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('subjects')) {
            Schema::table('subjects', function (Blueprint $table) {
                if (Schema::hasColumn('subjects', 'uuid')) {
                    $table->dropColumn('uuid');
                }
            });
        }
    }
};
