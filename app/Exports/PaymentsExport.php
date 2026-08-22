<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentsExport implements
    FromCollection,
    WithHeadings,
    WithMapping
{
    public function __construct(
        protected ?int $conferenceId = null
    ) {}

    public function collection()
    {
        $query = Payment::with([
            'participant.conference',
            'verifier',
        ]);

        if ($this->conferenceId) {
            $query->whereHas(
                'participant',
                function ($query) {
                    $query->where(
                        'conference_id',
                        $this->conferenceId
                    );
                }
            );
        }

        return $query
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'Payment Code',
            'Registration Number',
            'Participant',
            'Conference',
            'Amount',
            'Payment Method',
            'Status',
            'Paid At',
            'Verified At',
            'Verified By',
            'Notes',
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->payment_code,
            $payment->participant?->registration_number ?? '-',
            $payment->participant?->full_name ?? '-',
            $payment->participant?->conference?->name ?? '-',
            $payment->amount,
            ucwords(
                str_replace(
                    '_',
                    ' ',
                    $payment->payment_method
                )
            ),
            ucwords(
                str_replace(
                    '_',
                    ' ',
                    $payment->status
                )
            ),
            optional($payment->paid_at)
                ->format('d-m-Y H:i'),
            optional($payment->verified_at)
                ->format('d-m-Y H:i'),
            $payment->verifier?->name ?? '-',
            $payment->notes ?? '-',
        ];
    }
}
