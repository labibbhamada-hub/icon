<?php

namespace App\Exports;

use App\Models\Certificate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CertificatesExport implements
    FromCollection,
    WithHeadings,
    WithMapping
{
    public function __construct(
        protected ?int $conferenceId = null
    ) {}

    public function collection()
    {
        $query = Certificate::with([
            'participant',
            'conference',
            'submission',
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
            'Certificate Number',
            'Participant',
            'Registration Number',
            'Conference',
            'Certificate Type',
            'Submission Code',
            'Submission Title',
            'Issued At',
        ];
    }

    public function map($certificate): array
    {
        return [
            $certificate->certificate_number,

            $certificate->participant?->full_name ?? '-',

            $certificate->participant?->registration_number ?? '-',

            $certificate->conference?->name ?? '-',

            ucfirst($certificate->type),

            $certificate->submission?->submission_code ?? '-',

            $certificate->submission?->title ?? '-',

            optional($certificate->issued_at)
                ->format('d-m-Y H:i'),
        ];
    }
}
