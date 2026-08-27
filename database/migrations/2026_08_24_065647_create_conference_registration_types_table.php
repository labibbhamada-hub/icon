<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conference_registration_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conference_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 50);
            $table->string('category', 30);
            $table->string('payment_timing', 30)->default('immediate');
            $table->decimal('fee', 15, 2)->default(0);
            $table->unsignedInteger('included_papers')->default(0);
            $table->decimal('additional_paper_fee', 15, 2)->default(0);
            $table->string('currency', 10)->default('IDR');
            $table->text('description')->nullable();
            $table->text('benefits')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->unique(['conference_id', 'code']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('conference_registration_types');
    }
};
