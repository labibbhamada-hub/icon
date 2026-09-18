<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submission_authors', function (Blueprint $table) {
            $table->string('title_prefix', 50)
                ->nullable()
                ->after('submission_id');

            $table->string('title_suffix', 100)
                ->nullable()
                ->after('name');

            $table->string('orcid', 19)
                ->nullable()
                ->after('email');

            $table->index('orcid');
        });
    }

    public function down(): void
    {
        Schema::table('submission_authors', function (Blueprint $table) {
            $table->dropIndex(['orcid']);

            $table->dropColumn([
                'title_prefix',
                'title_suffix',
                'orcid',
            ]);
        });
    }
};
