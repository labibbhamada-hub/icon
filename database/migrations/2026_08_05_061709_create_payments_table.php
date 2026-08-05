<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');
            $table->foreignId('submission_id')->constrained()->onDelete('cascade')->nullable();
            $table->string('invoice_number');
            $table->string('amount');
            $table->string('payment_method');
            $table->string('proof');
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->string('verified_by');
            $table->string('verified_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
