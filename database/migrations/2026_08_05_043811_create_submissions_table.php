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
            $table->foreignId('conference_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('topic_id')->constrained()->onDelete('cascade');
            $table->string('submission_code');
            $table->string('title');
            $table->string('abstract');
            $table->string('keywords');
            $table->string('paper_file');
            $table->string('revised_file');
            $table->string('camera_ready_file');
            $table->enum('status', ['draft', 'submitted', 'under_review', 'revision', 'accepted', 'rejected', 'camera_ready', 'published'])->default('draft');
            $table->string('submitted_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
