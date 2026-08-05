<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conferences', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name', 50);
            $table->year('year');
            $table->text('theme')->nullable();
            $table->string('venue')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Indonesia');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('abstract_deadline')->nullable();
            $table->date('fullpaper_deadline')->nullable();
            $table->date('registration_deadline')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->enum('status', [
                'draft',
                'registration_open',
                'submission_open',
                'review',
                'camera_ready',
                'closed',
                'archived',
            ])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conferences');
    }
};
