<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained()->cascadeOnDelete();
            $table->decimal('score', 5, 2)->nullable();
            $table->text('comment')->nullable();
            $table->enum('recommendation', ['accept', 'minor_revision', 'major_revision', 'reject']);
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->unique(['submission_id', 'reviewer_id',]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
