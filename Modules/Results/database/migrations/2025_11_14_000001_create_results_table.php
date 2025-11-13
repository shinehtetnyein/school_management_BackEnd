<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('exam_id');
            $table->unsignedBigInteger('course_id');
            $table->float('marks');
            $table->string('grade')->nullable();
            $table->string('status'); // Pass/Fail
            $table->string('remarks')->nullable();
            $table->date('date');
            $table->timestamps();
            // Foreign keys can be added as needed
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
