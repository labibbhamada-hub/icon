<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('institution')->nullable();
            $table->string('faculty')->nullable();
            $table->string('country')->default('Indonesia');
            $table->string('phone', 30)->nullable();
            $table->text('address')->nullable();
            $table->string('photo')->nullable();
            $table->text('bio')->nullable();
            // Reviewer Information (nullable)
            $table->string('expertise')->nullable();
            $table->string('scopus_id')->nullable();
            $table->string('orcid')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
