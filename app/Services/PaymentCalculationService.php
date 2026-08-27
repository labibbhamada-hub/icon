<?php

namespace App\Services;

use App\Models\Participant;
use InvalidArgumentException;

class PaymentCalculationService
{
    public function acceptedPaperCount(
        Participant $participant
    ): int {
        $participant->loadMissing('submissions');

        return $participant
            ->submissions
            ->where('status', 'accepted')
            ->count();
    }

    public function calculate(
        Participant $participant
    ): array {
        $participant->loadMissing([
            'registrationType',
            'submissions',
        ]);

        $registrationType =
            $participant->registrationType;

        if (!$registrationType) {
            throw new InvalidArgumentException(
                'Participant registration type has not been configured.'
            );
        }

        $baseFee =
            (float) $registrationType->fee;

        $includedPapers =
            (int) $registrationType->included_papers;

        $additionalPaperFee =
            (float) $registrationType->additional_paper_fee;

        $acceptedPapers =
            $this->acceptedPaperCount(
                $participant
            );

        $additionalPapers = max(
            0,
            $acceptedPapers - $includedPapers
        );

        $additionalAmount =
            $additionalPapers *
            $additionalPaperFee;

        $totalAmount =
            $baseFee +
            $additionalAmount;

        $verifiedPaymentAmount =
            $this->verifiedPaymentAmount(
                $participant
            );

        $outstandingAmount = max(
            0,
            $totalAmount -
                $verifiedPaymentAmount
        );

        return [
            'registration_type_id' =>
            $registrationType->id,

            'registration_type_name' =>
            $registrationType->name,

            'payment_timing' =>
            $registrationType->payment_timing,

            'currency' =>
            strtoupper(
                $registrationType->currency
            ),

            'base_fee' =>
            $baseFee,

            'included_papers' =>
            $includedPapers,

            'accepted_papers' =>
            $acceptedPapers,

            'additional_papers' =>
            $additionalPapers,

            'additional_paper_fee' =>
            $additionalPaperFee,

            'additional_amount' =>
            $additionalAmount,

            'total_amount' =>
            $totalAmount,

            'verified_payment_amount' =>
            $verifiedPaymentAmount,

            'outstanding_amount' =>
            $outstandingAmount,
        ];
    }

    public function canPay(
        Participant $participant
    ): bool {
        $participant->loadMissing([
            'registrationType',
            'submissions',
        ]);

        $registrationType =
            $participant->registrationType;

        if (!$registrationType) {
            return false;
        }

        if (
            $registrationType->payment_timing ===
            'immediate'
        ) {
            return $participant->registration_status ===
                'pending'
                && $this->outstandingAmount(
                    $participant
                ) > 0;
        }

        if (
            $registrationType->payment_timing ===
            'after_acceptance'
        ) {
            return $this->acceptedPaperCount(
                $participant
            ) > 0
                && $this->outstandingAmount(
                    $participant
                ) > 0;
        }

        return false;
    }

    public function verifiedPaymentAmount(
        Participant $participant
    ): float {
        return (float) $participant
            ->payments()
            ->where('status', 'verified')
            ->sum('amount');
    }

    public function outstandingAmount(
        Participant $participant
    ): float {
        $calculation = $this->calculate(
            $participant
        );

        $verifiedAmount =
            $this->verifiedPaymentAmount(
                $participant
            );

        return max(
            0,
            $calculation['total_amount']
                - $verifiedAmount
        );
    }
}
