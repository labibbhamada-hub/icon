<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conference_whatsapp_groups', function (Blueprint $table) {
            $table->id();

            $table->foreignId('conference_id')
                ->constrained()
                ->cascadeOnDelete()
                ->unique();

            $table->string('title');

            $table->text('group_url');

            $table->text('description')
                ->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conference_whatsapp_groups');
    }
};
