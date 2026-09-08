<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('conference_online_meetings', function (Blueprint $table) {
            $table->unique(
                'conference_id',
                'conference_online_meetings_conference_id_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conference_online_meetings', function (Blueprint $table) {
            $table->dropUnique(
                'conference_online_meetings_conference_id_unique'
            );
        });
    }
};
