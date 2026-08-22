<?php

namespace App\Exports;

use App\Models\Participant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ParticipantsExport implements
    FromCollection,
    WithHeadings,
    WithMapping
{
    public function __construct(
        protected ?int $conferenceId = null
    ) {}

    public function collection()
    {
        $query = Participant::with([
            'conference',
            'user',
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
            'Registration Number',
            'Name',
            'Email',
            'Conference',
            'Participant Type',
            'Attendance Type',
            'Institution',
            'Department',
            'Country',
            'City',
            'Registration Status',
            'Registered At',
        ];
    }

    public function map($participant): array
    {
        return [
            $participant->registration_number,
            $participant->full_name,
            $participant->email,
            $participant->conference?->name ?? '-',
            ucfirst($participant->participant_type),
            ucfirst($participant->attendance_type),
            $participant->institution ?? '-',
            $participant->department ?? '-',
            $participant->country ?? '-',
            $participant->city ?? '-',
            ucwords(
                str_replace(
                    '_',
                    ' ',
                    $participant->registration_status
                )
            ),
            optional($participant->registered_at)
                ->format('d-m-Y H:i'),
        ];
    }
}
