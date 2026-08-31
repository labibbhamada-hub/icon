<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('conference_id')
                ->constrained('conferences')
                ->cascadeOnDelete();

            $table->foreignId('registration_type_id')
                ->nullable()
                ->constrained('conference_registration_types')
                ->nullOnDelete();

            $table->string('registration_number', 50)
                ->unique();

            $table->string('full_name');

            $table->string('email');

            $table->string('phone', 50)
                ->nullable();

            $table->string('institution')
                ->nullable();

            $table->string('department')
                ->nullable();

            $table->string('country', 100)
                ->default('Indonesia');

            $table->string('city', 100)
                ->nullable();

            $table->string('participant_type', 30)
                ->default('regular');

            $table->string('attendance_type', 20)
                ->default('offline');

            $table->string('registration_status', 20)
                ->default('pending');

            $table->text('notes')
                ->nullable();

            $table->timestamp('registered_at')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'user_id',
                'conference_id',
            ]);

            $table->index('conference_id');
            $table->index('email');
            $table->index('registration_type_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
