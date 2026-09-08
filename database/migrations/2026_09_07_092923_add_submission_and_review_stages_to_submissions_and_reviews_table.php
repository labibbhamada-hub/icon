<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->string('submission_stage', 20)
                ->default('full_paper')
                ->after('keywords');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->string('review_stage', 20)
                ->default('full_paper')
                ->after('reviewer_id');

            $table->dropUnique([
                'submission_id',
                'reviewer_id',
                'review_round',
            ]);

            $table->unique(
                [
                    'submission_id',
                    'reviewer_id',
                    'review_stage',
                    'review_round',
                ],
                'reviews_submission_stage_round_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique(
                'reviews_submission_stage_round_unique'
            );

            $table->unique([
                'submission_id',
                'reviewer_id',
                'review_round',
            ]);

            $table->dropColumn('review_stage');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn('submission_stage');
        });
    }
};
