<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Add audience column
        |--------------------------------------------------------------------------
        |
        | The previous migration attempt may already have added this column
        | before failing while changing the index structure.
        |
        */

        if (!Schema::hasColumn('conference_whatsapp_groups', 'audience')) {
            Schema::table('conference_whatsapp_groups', function (Blueprint $table) {
                $table->string('audience', 20)
                    ->default('presenter')
                    ->after('conference_id');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Keep a normal index for the foreign key
        |--------------------------------------------------------------------------
        |
        | MySQL requires an index for conference_id because it is referenced
        | by the foreign key. The existing unique index cannot be dropped
        | until another index exists.
        |
        */

        Schema::table('conference_whatsapp_groups', function (Blueprint $table) {
            $table->index(
                'conference_id',
                'conference_whatsapp_groups_conference_id_index'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Replace conference-only uniqueness with conference + audience
        |--------------------------------------------------------------------------
        */

        Schema::table('conference_whatsapp_groups', function (Blueprint $table) {
            $table->dropUnique(
                'conference_whatsapp_groups_conference_id_unique'
            );

            $table->unique(
                ['conference_id', 'audience'],
                'conference_whatsapp_groups_conference_audience_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('conference_whatsapp_groups', function (Blueprint $table) {
            $table->dropUnique(
                'conference_whatsapp_groups_conference_audience_unique'
            );

            $table->dropIndex(
                'conference_whatsapp_groups_conference_id_index'
            );
        });

        Schema::table('conference_whatsapp_groups', function (Blueprint $table) {
            $table->unique(
                'conference_id',
                'conference_whatsapp_groups_conference_id_unique'
            );

            $table->dropColumn('audience');
        });
    }
};
