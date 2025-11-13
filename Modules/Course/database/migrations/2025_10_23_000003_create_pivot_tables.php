<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pivot table for course enrollments (students)
        Schema::create('course_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->date('enrollment_date');
            $table->enum('status', ['active', 'completed', 'dropped'])->default('active');
            $table->timestamps();

            $table->unique(['user_id', 'course_id']);
        });

        // Pivot table for subject teachers
        Schema::create('subject_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->date('assigned_date');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->unique(['user_id', 'subject_id']);
        });

        // Pivot table for course teachers
        Schema::create('course_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->date('assigned_date');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->unique(['user_id', 'course_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_teacher');
        Schema::dropIfExists('subject_teacher');
        Schema::dropIfExists('course_student');
    }
};
