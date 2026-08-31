<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('conference_id')
                ->constrained('conferences')
                ->cascadeOnDelete();

            $table->foreignId('participant_id')
                ->constrained('participants')
                ->cascadeOnDelete();

            $table->foreignId('topic_id')
                ->constrained('topics')
                ->cascadeOnDelete();

            $table->string('submission_code', 50)
                ->unique();

            $table->string('title');

            $table->text('abstract');

            $table->text('keywords');

            $table->string('paper_file');

            $table->string('revised_file')
                ->nullable();

            $table->string('camera_ready_file')
                ->nullable();

            $table->text('camera_ready_correction_reason')
                ->nullable();

            $table->enum('status', [
                'draft',
                'submitted',
                'under_review',
                'revision',
                'accepted',
                'rejected',
                'camera_ready',
                'published',
            ])->default('draft');

            $table->string('presentation_type', 20)
                ->nullable();

            $table->string('presentation_mode', 20)
                ->nullable();

            $table->unsignedBigInteger('presenter_author_id')
                ->nullable();

            $table->boolean('presentation_completed')
                ->default(false);

            $table->timestamp('submitted_at')
                ->nullable();

            $table->timestamps();

            $table->index('presenter_author_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
