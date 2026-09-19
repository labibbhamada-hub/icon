<?php

namespace App\Services;

use App\Models\Participant;
use InvalidArgumentException;

class PaymentCalculationService
{
    /**
     * Count accepted full papers for the participant.
     *
     * Kept for backward compatibility with existing callers/tests.
     * BHAMADA ICON 2026 business rules allow one presenter to submit one paper,
     * so this value is informational and is not used to add fees.
     */
    public function acceptedPaperCount(Participant $participant): int
    {
        $participant->loadMissing('submissions');

        return $participant->submissions
            ->where('submission_stage', 'full_paper')
            ->where('status', 'accepted')
            ->count();
    }

    /**
     * Presentation type is no longer part of the payment calculation.
     *
     * Kept as a compatibility method so existing callers do not break while
     * the legacy Oral/Poster flow is retired.
     */
    public function presentationType(Participant $participant): ?string
    {
        return null;
    }

    /**
     * Presentation-specific pricing is no longer used.
     *
     * Kept as a compatibility method while the legacy pricing table is
     * retired from the application flow.
     */
    public function presentationPrice(Participant $participant): ?float
    {
        return null;
    }

    public function verifiedPaymentAmount(Participant $participant): float
    {
        return (float) $participant
            ->payments()
            ->where('status', 'verified')
            ->sum('amount');
    }

    public function calculate(Participant $participant): array
    {
        $participant->loadMissing([
            'registrationType',
            'submissions',
        ]);

        $registrationType = $participant->registrationType;

        if (!$registrationType) {
            throw new InvalidArgumentException(
                'Participant registration type has not been configured.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | BHAMADA ICON 2026 payment rule
        |--------------------------------------------------------------------------
        |
        | Payment is based only on the selected registration type fee.
        |
        | Presenter = 1 presenter + 1 paper + 1 video.
        | There is no Oral/Poster pricing and no additional-paper fee.
        |--------------------------------------------------------------------------
        */

        $baseFee = (float) $registrationType->fee;
        $currency = strtoupper((string) $registrationType->currency);

        $includedPapers = min(1, max(0, (int) $registrationType->included_papers));
        $acceptedPapers = $this->acceptedPaperCount($participant);

        // Additional papers are not part of the current conference business rule.
        $additionalPapers = 0;
        $additionalPaperFee = 0.0;
        $additionalAmount = 0.0;

        $totalAmount = $baseFee;
        $verifiedPaymentAmount = $this->verifiedPaymentAmount($participant);
        $outstandingAmount = max(
            0.0,
            $totalAmount - $verifiedPaymentAmount
        );

        return [
            'registration_type_id' => $registrationType->id,
            'registration_type_name' => $registrationType->name,
            'payment_timing' => $registrationType->payment_timing,
            'presentation_type' => null,
            'presentation_fee' => null,
            'currency' => $currency,
            'base_fee' => $baseFee,
            'included_papers' => $includedPapers,
            'accepted_papers' => $acceptedPapers,
            'additional_papers' => $additionalPapers,
            'additional_paper_fee' => $additionalPaperFee,
            'additional_amount' => $additionalAmount,
            'total_amount' => $totalAmount,
            'verified_payment_amount' => $verifiedPaymentAmount,
            'outstanding_amount' => $outstandingAmount,
        ];
    }

    public function outstandingAmount(Participant $participant): float
    {
        return $this->calculate($participant)['outstanding_amount'];
    }

    public function canPay(Participant $participant): bool
    {
        $participant->loadMissing('registrationType');

        $registrationType = $participant->registrationType;

        if (!$registrationType) {
            return false;
        }

        // All ICON 2026 registration payments are due before abstract submission.
        if ($registrationType->payment_timing !== 'immediate') {
            return false;
        }

        return $participant->registration_status === 'pending'
            && $this->outstandingAmount($participant) > 0;
    }
}
