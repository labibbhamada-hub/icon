<?php

namespace App\Services;

use App\Models\Participant;
use Illuminate\Support\Collection;
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

    public function presentationType(
        Participant $participant
    ): ?string {
        $participant->loadMissing([
            'registrationType',
            'submissions',
        ]);

        if (
            $participant->registrationType?->category !==
            'presenter'
        ) {
            return null;
        }

        return $participant
            ->submissions
            ->whereNotNull('presentation_type')
            ->sortBy('id')
            ->first()
            ?->presentation_type;
    }

    public function presentationPrice(
        Participant $participant
    ): ?float {
        $participant->loadMissing([
            'registrationType.presentationPrices',
            'submissions',
        ]);

        if (
            $participant->registrationType?->category !==
            'presenter'
        ) {
            return null;
        }

        $presentationType =
            $this->presentationType(
                $participant
            );

        if (!$presentationType) {
            return null;
        }

        $price =
            $participant
            ->registrationType
            ->presentationPrices
            ->firstWhere(
                'presentation_type',
                $presentationType
            );

        if (
            !$price ||
            !$price->is_active
        ) {
            return null;
        }

        return (float) $price->fee;
    }

    public function verifiedPaymentAmount(
        Participant $participant
    ): float {
        return (float) $participant
            ->payments()
            ->where(
                'status',
                'verified'
            )
            ->sum('amount');
    }

    public function calculate(
        Participant $participant
    ): array {
        $participant->loadMissing([
            'registrationType.presentationPrices',
            'submissions',
        ]);

        $registrationType =
            $participant->registrationType;

        if (!$registrationType) {
            throw new InvalidArgumentException(
                'Participant registration type has not been configured.'
            );
        }

        $presentationType =
            $this->presentationType(
                $participant
            );

        $presentationPrice =
            $this->presentationPrice(
                $participant
            );

        /*
        |--------------------------------------------------------------------------
        | Determine base fee
        |--------------------------------------------------------------------------
        |
        | Regular participants use the registration type fee.
        |
        | Presenters use the configured presentation price:
        | Oral / Poster.
        |
        */

        if (
            $registrationType->category ===
            'presenter'
        ) {
            if (
                !$presentationType ||
                $presentationPrice === null
            ) {
                throw new InvalidArgumentException(
                    'Presentation type and pricing have not been configured for this registration.'
                );
            }

            $baseFee =
                $presentationPrice;
        } else {
            $baseFee =
                (float) $registrationType->fee;
        }

        $includedPapers =
            (int) $registrationType->included_papers;

        $additionalPaperFee =
            (float) $registrationType->additional_paper_fee;

        $acceptedPapers =
            $this->acceptedPaperCount(
                $participant
            );

        $additionalPapers =
            max(
                0,
                $acceptedPapers -
                    $includedPapers
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

        $outstandingAmount =
            max(
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

            'presentation_type' =>
            $presentationType,

            'presentation_fee' =>
            $presentationPrice,

            'currency' =>
            strtoupper(
                $presentationPrice !== null
                    ? (
                        $registrationType
                        ->presentationPrices
                        ->firstWhere(
                            'presentation_type',
                            $presentationType
                        )
                        ?->currency
                        ?? $registrationType->currency
                    )
                    : $registrationType->currency
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

    public function outstandingAmount(
        Participant $participant
    ): float {
        $participant->loadMissing([
            'registrationType.presentationPrices',
            'submissions',
        ]);

        $calculation =
            $this->calculate(
                $participant
            );

        return $calculation['outstanding_amount'];
    }

    public function canPay(
        Participant $participant
    ): bool {
        $participant->loadMissing([
            'registrationType.presentationPrices',
            'submissions',
        ]);

        $registrationType =
            $participant->registrationType;

        if (!$registrationType) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Presenter
        |--------------------------------------------------------------------------
        */

        if (
            $registrationType->category ===
            'presenter'
        ) {
            if (
                $registrationType->payment_timing !==
                'after_acceptance'
            ) {
                return false;
            }

            if (
                $this->acceptedPaperCount(
                    $participant
                ) <= 0
            ) {
                return false;
            }

            if (
                !$this->presentationType(
                    $participant
                )
            ) {
                return false;
            }

            return $this->outstandingAmount(
                $participant
            ) > 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Immediate payment participants
        |--------------------------------------------------------------------------
        */

        if (
            $registrationType->payment_timing ===
            'immediate'
        ) {
            return (
                $participant->registration_status ===
                'pending'
            )
                && $this->outstandingAmount(
                    $participant
                ) > 0;
        }

        return false;
    }
}
