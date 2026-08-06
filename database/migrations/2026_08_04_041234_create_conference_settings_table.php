<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conference_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conference_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_active')->default(false);
            $table->boolean('registration_enabled')->default(false);
            $table->boolean('submission_enabled')->default(false);
            $table->boolean('payment_enabled')->default(false);
            $table->boolean('review_enabled')->default(false);
            $table->boolean('certificate_enabled')->default(false);
            $table->boolean('published')->default(false);
            $table->boolean('maintenance_mode')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
