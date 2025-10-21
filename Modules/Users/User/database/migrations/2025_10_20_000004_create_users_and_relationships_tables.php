<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('profile_photo')->nullable();
            $table->enum('type', ['student', 'teacher', 'staff'])->default('student');
            $table->string('student_id')->nullable()->unique();
            $table->string('staff_id')->nullable()->unique();
            $table->boolean('status')->default(true);
            $table->date('joined_date')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            // Ensure either student_id or staff_id is filled based on type
            $table->index(['type', 'student_id']);
            $table->index(['type', 'staff_id']);
        });

        // Pivot table for Course-Student relationship
        Schema::create('course_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->date('enrollment_date');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->unique(['course_id', 'student_id']);
        });

        // Pivot table for Subject-Teacher relationship
        Schema::create('subject_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->date('assigned_date');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->unique(['subject_id', 'teacher_id']);
        });

        // Pivot table for Course-Teacher relationship
        Schema::create('course_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->date('assigned_date');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->unique(['course_id', 'teacher_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_teacher');
        Schema::dropIfExists('subject_teacher');
        Schema::dropIfExists('course_student');
        Schema::dropIfExists('users');
    }
};
