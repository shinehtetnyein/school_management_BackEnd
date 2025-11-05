<?php
// Modules/Course/database/migrations/2024_01_01_000000_create_courses_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_name');
            $table->text('description')->nullable();
            $table->string('category');
            $table->timestamps();
            $table->index('course_name');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
}
