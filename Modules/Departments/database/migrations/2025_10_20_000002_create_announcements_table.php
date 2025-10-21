<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->dateTime('publish_date');
            $table->dateTime('expiry_date')->nullable();
            $table->integer('priority')->default(0);
            $table->string('category')->comment('academic, administrative, events, emergency, extracurricular, general');
            $table->string('target_audience')->default('all');
            $table->json('grade_level')->nullable();
            $table->boolean('requires_acknowledgment')->default(false);
            $table->json('attachments')->nullable();
            $table->morphs('announceable');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('announcements');
    }
};
