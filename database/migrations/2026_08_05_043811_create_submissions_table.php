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
            $table->foreignId('conference_id')->constrained()->cascadeOnDelete();
            $table->foreignId('participant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('topic_id')->constrained()->cascadeOnDelete();
            $table->string('submission_code')->unique();
            $table->string('title');
            $table->text('abstract');
            $table->text('keywords');
            $table->string('paper_file');
            $table->string('revised_file')->nullable();
            $table->string('camera_ready_file')->nullable();
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
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
