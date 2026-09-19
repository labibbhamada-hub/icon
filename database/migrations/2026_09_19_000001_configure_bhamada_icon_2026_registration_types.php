<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $conference = DB::table('conferences')
            ->where('year', 2026)
            ->first();

        if (!$conference) {
            return;
        }

        $conferenceId = $conference->id;

        $registrationTypes = [
            [
                'name' => 'Presenter Bhamada',
                'code' => 'presenter_bhamada',
                'category' => 'presenter',
                'fee' => 250000,
                'currency' => 'IDR',
                'included_papers' => 1,
                'additional_paper_fee' => 0,
                'payment_timing' => 'immediate',
                'description' => 'Presenter from Universitas Bhamada.',
                'benefits' => 'Presentation of 1 paper.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Presenter Luar Bhamada',
                'code' => 'presenter_luar_bhamada',
                'category' => 'presenter',
                'fee' => 400000,
                'currency' => 'IDR',
                'included_papers' => 1,
                'additional_paper_fee' => 0,
                'payment_timing' => 'immediate',
                'description' => 'Presenter from institutions outside Universitas Bhamada.',
                'benefits' => 'Presentation of 1 paper.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Presenter Overseas',
                'code' => 'presenter_overseas',
                'category' => 'presenter',
                'fee' => 60,
                'currency' => 'USD',
                'included_papers' => 1,
                'additional_paper_fee' => 0,
                'payment_timing' => 'immediate',
                'description' => 'Overseas presenter.',
                'benefits' => 'Presentation of 1 paper.',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Peserta Seminar Bhamada',
                'code' => 'peserta_seminar_bhamada',
                'category' => 'participant',
                'fee' => 10000,
                'currency' => 'IDR',
                'included_papers' => 0,
                'additional_paper_fee' => 0,
                'payment_timing' => 'immediate',
                'description' => 'Seminar participant from Universitas Bhamada.',
                'benefits' => 'Conference seminar participation.',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Peserta Seminar Umum',
                'code' => 'peserta_seminar_umum',
                'category' => 'participant',
                'fee' => 75000,
                'currency' => 'IDR',
                'included_papers' => 0,
                'additional_paper_fee' => 0,
                'payment_timing' => 'immediate',
                'description' => 'General seminar participant.',
                'benefits' => 'Conference seminar participation.',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($registrationTypes as $type) {
            DB::table('conference_registration_types')->updateOrInsert(
                [
                    'conference_id' => $conferenceId,
                    'code' => $type['code'],
                ],
                [
                    ...$type,
                    'conference_id' => $conferenceId,
                    'updated_at' => now(),
                ]
            );
        }

        DB::table('conference_registration_types')
            ->where('conference_id', $conferenceId)
            ->whereNotIn(
                'code',
                collect($registrationTypes)
                    ->pluck('code')
                    ->all()
            )
            ->update([
                'is_active' => false,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        $conference = DB::table('conferences')
            ->where('year', 2026)
            ->first();

        if (!$conference) {
            return;
        }

        DB::table('conference_registration_types')
            ->where('conference_id', $conference->id)
            ->whereIn('code', [
                'presenter_bhamada',
                'presenter_luar_bhamada',
                'presenter_overseas',
                'peserta_seminar_bhamada',
                'peserta_seminar_umum',
            ])
            ->delete();
    }
};
