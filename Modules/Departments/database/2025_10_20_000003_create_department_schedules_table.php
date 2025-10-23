<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('department_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->string('grade_level');
            $table->string('section');
            $table->integer('day_of_week'); // 0 = Sunday, 6 = Saturday
            $table->string('period_type')->default('regular');
            $table->integer('period_number');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room');
            $table->string('status')->default('scheduled');
            $table->date('date')->nullable();
            $table->foreignId('substitution_teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->boolean('attendance_required')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('department_schedules');
    }
};
