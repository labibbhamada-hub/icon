<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conference_attendance_options', function (Blueprint $table) {
            $table->id();

            $table->foreignId('conference_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('type', 20);

            $table->unsignedTinyInteger('sort_order')
                ->default(0);

            $table->timestamps();

            $table->unique([
                'conference_id',
                'type',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conference_attendance_options');
    }
};
