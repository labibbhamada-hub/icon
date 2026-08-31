<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conference_configurations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('conference_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            // Branding
            $table->string('logo')
                ->nullable();

            $table->string('signature_file')
                ->nullable();

            // Certificate
            $table->string('chair_name')
                ->nullable();

            $table->string('chair_title')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conference_configurations');
    }
};
