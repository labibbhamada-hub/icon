<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn([
                'presentation_type',
                'presentation_mode',
                'presentation_completed',
            ]);
        });

        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn('presentation_type');
        });
    }

    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->string('presentation_type', 20)
                ->nullable()
                ->after('attendance_type');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->string('presentation_type', 20)
                ->nullable()
                ->after('status');

            $table->string('presentation_mode', 20)
                ->nullable()
                ->after('presentation_type');

            $table->boolean('presentation_completed')
                ->default(false)
                ->after('presenter_author_id');
        });
    }
};
