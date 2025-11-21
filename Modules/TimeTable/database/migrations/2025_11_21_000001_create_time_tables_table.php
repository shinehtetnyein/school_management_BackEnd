<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('time_tables', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable()->unique();
            $table->foreignId('classroom_id')->constrained('classrooms')->onDelete('cascade');
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('day_of_week', 50); // Monday, Tuesday, etc.
            $table->time('start_time');
            $table->time('end_time');
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('set null');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('room_number', 100)->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            // Indexes for faster queries
            $table->index(['classroom_id', 'section_id', 'day_of_week']);
            $table->index(['course_id', 'day_of_week']);
            $table->index(['teacher_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_tables');
    }
};
