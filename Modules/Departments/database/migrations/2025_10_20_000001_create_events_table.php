<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->string('location')->nullable();
            $table->string('status')->default('upcoming');
            $table->string('event_type')->comment('academic, athletic, cultural, admin, club, assembly, exam, field_trip');
            $table->json('grade_level')->nullable();
            $table->integer('max_participants')->nullable();
            $table->boolean('requires_permission')->default(false);
            $table->json('additional_info')->nullable();
            $table->morphs('eventable');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
};
