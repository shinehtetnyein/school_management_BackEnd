<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('type')->comment('academic, administrative, support');
            $table->text('description')->nullable();
            $table->foreignId('head_of_department')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('status')->default(true);
            $table->string('location')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->json('grade_levels')->nullable()->comment('For academic departments: array of grade levels this department serves');
            $table->json('subjects')->nullable()->comment('For academic departments: array of subjects under this department');
            $table->integer('staff_count')->default(0);
            $table->json('office_hours')->nullable();
            $table->timestamps();

            // Add indexes for commonly queried fields
            $table->index('status');
            $table->index('establishment_date');
            $table->index(['name', 'code']); // For search functionality
        });

        // Add department_id column to subjects table if it doesn't exist
        if (!Schema::hasColumn('subjects', 'department_id')) {
            Schema::table('subjects', function (Blueprint $table) {
                $table->foreignId('department_id')->nullable()->after('id')->constrained()->nullOnDelete();
                $table->index('department_id');
            });
        }
    }

    public function down(): void
    {
        // Remove department_id from subjects first
        if (Schema::hasColumn('subjects', 'department_id')) {
            Schema::table('subjects', function (Blueprint $table) {
                $table->dropConstrainedForeignId('department_id');
            });
        }

        Schema::dropIfExists('departments');
    }
};
