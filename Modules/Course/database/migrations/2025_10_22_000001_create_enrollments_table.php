<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrollmentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->timestamp('enrolled_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->timestamp('created_at')->useCurrent();
            $table->enum('status', ['active', 'completed', 'dropped'])->default('active');
            $table->string('class_level');

            $table->unique(['user_id', 'course_id']);
            $table->index('status');
            $table->index('enrolled_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
}
