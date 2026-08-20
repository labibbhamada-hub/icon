<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('conference_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('submission_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('certificate_number')
                ->unique();
            $table->enum('type', [
                'participant',
                'presenter',
                'speaker',
                'committee',
                'reviewer',
            ])->default('participant');
            $table->string('file_path')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();
            $table->unique(['participant_id', 'conference_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
