<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conference_attendances', function (Blueprint $table) {
            $table->id();

            $table
                ->foreignId('conference_id')
                ->constrained('conferences')
                ->cascadeOnDelete();

            $table
                ->foreignId('participant_id')
                ->constrained('participants')
                ->cascadeOnDelete();

            $table->timestamp('checked_in_at')->nullable();

            $table
                ->string('attendance_status', 20)
                ->default('not_checked_in');

            $table->timestamp('verified_at')->nullable();

            $table
                ->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('verification_notes')->nullable();

            $table->timestamps();

            $table->unique(
                ['conference_id', 'participant_id'],
                'conference_attendances_conference_participant_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conference_attendances');
    }
};
