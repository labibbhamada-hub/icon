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
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('conference_id')->constrained()->cascadeOnDelete();
            $table->string('registration_number')->unique();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('institution')->nullable();
            $table->string('department')->nullable();
            $table->string('country')->default('Indonesia');
            $table->string('city')->nullable();
            $table->enum('participant_type', ['regular', 'student', 'speaker', 'committee'])->default('regular');
            $table->enum('attendance_type', ['offline', 'online', 'hybrid'])->default('offline');
            $table->enum('registration_status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('registered_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'conference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
