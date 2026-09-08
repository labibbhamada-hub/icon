<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conference_whatsapp_groups', function (Blueprint $table) {
            $table->unique(
                'conference_id',
                'conference_whatsapp_groups_conference_id_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('conference_whatsapp_groups', function (Blueprint $table) {
            $table->dropUnique(
                'conference_whatsapp_groups_conference_id_unique'
            );
        });
    }
};
