<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conference_configurations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('conference_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            // Branding
            $table->string('logo')
                ->nullable();

            $table->string('signature_file')
                ->nullable();

            // Payment
            $table->string('bank_name')
                ->nullable();

            $table->string('account_number')
                ->nullable();

            $table->string('account_name')
                ->nullable();

            $table->decimal('regular_fee', 15, 2)
                ->default(0);

            $table->decimal('student_fee', 15, 2)
                ->default(0);

            // Certificate
            $table->string('chair_name')
                ->nullable();

            $table->string('chair_title')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conference_configurations');
    }
};
