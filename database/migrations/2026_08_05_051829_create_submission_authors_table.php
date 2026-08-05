<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submission_authors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('institution');
            $table->string('country');
            $table->boolean('is_corresponding');
            $table->string('author_order');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_authors');
    }
};
