<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->string('title_prefix', 50)
                ->nullable()
                ->after('registration_number');

            $table->string('title_suffix', 100)
                ->nullable()
                ->after('full_name');

            $table->string('orcid', 19)
                ->nullable()
                ->after('email');

            $table->index('orcid');
        });
    }

    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropIndex(['orcid']);
            $table->dropColumn([
                'title_prefix',
                'title_suffix',
                'orcid',
            ]);
        });
    }
};
