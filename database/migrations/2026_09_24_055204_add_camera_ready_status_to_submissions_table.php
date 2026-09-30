<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table
                ->string('camera_ready_status', 20)
                ->default('not_submitted')
                ->after('camera_ready_file');
        });

        DB::table('submissions')
            ->where('status', 'published')
            ->whereNotNull('camera_ready_file')
            ->update([
                'camera_ready_status' => 'approved',
            ]);

        DB::table('submissions')
            ->where('status', '!=', 'published')
            ->whereNotNull('camera_ready_file')
            ->update([
                'camera_ready_status' => 'submitted',
            ]);
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn('camera_ready_status');
        });
    }
};
