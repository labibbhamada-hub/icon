<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conference_online_meetings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('conference_id')
                ->constrained()
                ->cascadeOnDelete()
                ->unique();

            $table->string('title');

            $table->text('meeting_url');

            $table->string('meeting_id', 100)
                ->nullable();

            $table->string('passcode', 100)
                ->nullable();

            $table->text('instructions')
                ->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conference_online_meetings');
    }
};
