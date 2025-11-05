<?php
// Modules/Subject/database/migrations/2024_01_01_000000_create_subjects_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('subjects')) {
            Schema::create('subjects', function (Blueprint $table) {
                $table->id();
                $table->string('subject_code')->unique();
                $table->string('subject_name');
                $table->text('subject_desc')->nullable();
                $table->enum('class_level', ['beginner', 'intermediate', 'advanced']);
                $table->enum('status', ['active', 'inactive'])->default('active');
                // $table->foreignId('exam_id')->nullable()->constrained('exams')->onDelete('set null');
                $table->timestamps();

                $table->index('subject_code');
                $table->index('subject_name');
                $table->index('class_level');
                $table->index('status');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
