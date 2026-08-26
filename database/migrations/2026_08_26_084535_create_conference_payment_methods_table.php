<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conference_payment_methods', function (Blueprint $table) {
            $table->id();

            $table->foreignId('conference_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('type', 30);

            $table->string('name', 100);

            $table->string('provider', 100)
                ->nullable();

            $table->string('account_number', 100)
                ->nullable();

            $table->string('account_name', 255)
                ->nullable();

            $table->string('currency', 3)
                ->default('IDR');

            $table->text('instructions')
                ->nullable();

            $table->string('qr_code_file', 255)
                ->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->unsignedTinyInteger('sort_order')
                ->default(0);

            $table->timestamps();

            $table->index([
                'conference_id',
                'is_active',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conference_payment_methods');
    }
};
