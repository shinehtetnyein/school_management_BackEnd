<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if uuid column already exists
        if (!Schema::hasColumn('courses', 'uuid')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->uuid('uuid')->unique()->after('id')->nullable();
            });
        }

        // Generate UUIDs for existing courses that have null uuid
        $coursesWithoutUuid = DB::table('courses')->whereNull('uuid')->get();
        foreach ($coursesWithoutUuid as $course) {
            DB::table('courses')
                ->where('id', $course->id)
                ->update(['uuid' => (string) \Illuminate\Support\Str::uuid()]);
        }

        // Make uuid not nullable after setting values
        if (Schema::hasColumn('courses', 'uuid')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->uuid('uuid')->nullable(false)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropColumn('uuid');
        });
    }
};
