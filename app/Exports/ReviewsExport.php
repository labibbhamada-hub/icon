<?php

namespace App\Exports;

use App\Models\Review;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReviewsExport implements
    FromCollection,
    WithHeadings,
    WithMapping
{
    public function __construct(
        protected ?int $conferenceId = null
    ) {}

    public function collection()
    {
        $query = Review::with([
            'submission.conference',
            'submission.participant',
            'submission.topic',
            'reviewer.user',
        ]);

        if ($this->conferenceId) {
            $query->whereHas(
                'submission',
                function ($query) {
                    $query->where(
                        'conference_id',
                        $this->conferenceId
                    );
                }
            );
        }

        return $query
            ->orderBy('submission_id')
            ->orderBy('review_round')
            ->orderBy('reviewer_id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Submission Code',
            'Submission Title',
            'Participant',
            'Registration Number',
            'Conference',
            'Topic',
            'Reviewer',
            'Review Round',
            'Score',
            'Recommendation',
            'Status',
            'Reviewed At',
        ];
    }

    public function map($review): array
    {
        return [
            $review->submission?->submission_code ?? '-',

            $review->submission?->title ?? '-',

            $review->submission?->participant?->full_name ?? '-',

            $review->submission?->participant?->registration_number ?? '-',

            $review->submission?->conference?->name ?? '-',

            $review->submission?->topic?->name ?? '-',

            $review->reviewer?->user?->name ?? '-',

            'Round ' . ($review->review_round ?? 1),

            $review->score ?? '-',

            $review->recommendation
                ? ucwords(
                    str_replace(
                        '_',
                        ' ',
                        $review->recommendation
                    )
                )
                : 'Pending',

            $review->reviewed_at
                ? 'Completed'
                : 'Pending',

            optional($review->reviewed_at)
                ->format('d-m-Y H:i'),
        ];
    }
}
