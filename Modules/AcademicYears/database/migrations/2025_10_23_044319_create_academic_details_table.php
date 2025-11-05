<?php
// Modules/Academic/database/migrations/2024_01_01_000001_create_academic_details_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicDetailsTable extends Migration
{
    public function up(): void
    {
        Schema::create('academic_details', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_id')->constrained('academic_years')->onDelete('cascade');
            $table->primary(['user_id', 'academic_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_details');
    }
}
