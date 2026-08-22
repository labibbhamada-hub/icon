<?php

namespace App\Exports;

use App\Models\Submission;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SubmissionsExport implements
    FromCollection,
    WithHeadings,
    WithMapping
{
    public function __construct(
        protected ?int $conferenceId = null
    ) {}

    public function collection()
    {
        $query = Submission::with([
            'conference',
            'participant',
            'topic',
        ]);

        if ($this->conferenceId) {
            $query->where(
                'conference_id',
                $this->conferenceId
            );
        }

        return $query
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'Submission Code',
            'Title',
            'Participant',
            'Registration Number',
            'Conference',
            'Topic',
            'Status',
            'Submitted At',
        ];
    }

    public function map($submission): array
    {
        return [
            $submission->submission_code,
            $submission->title,
            $submission->participant?->full_name ?? '-',
            $submission->participant?->registration_number ?? '-',
            $submission->conference?->name ?? '-',
            $submission->topic?->name ?? '-',
            ucwords(
                str_replace(
                    '_',
                    ' ',
                    $submission->status
                )
            ),
            optional($submission->submitted_at)
                ->format('d-m-Y H:i'),
        ];
    }
}
