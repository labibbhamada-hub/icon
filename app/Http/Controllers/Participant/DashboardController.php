<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\ImportantDate;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $participants = Participant::with([
            'conference.setting',
            'registrationType',
            'submissions.topic',
        ])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        $payments = Payment::whereIn(
            'participant_id',
            $participants->pluck('id')
        )
            ->latest()
            ->get()
            ->groupBy('participant_id')
            ->map(function ($items) {
                return $items->first();
            });
        $importantDates = ImportantDate::whereIn(
            'conference_id',
            $participants->pluck('conference_id')->unique()
        )
            ->where('is_active', true)
            ->whereIn('type', [
                'registration',
                'full_paper_submission',
                'revision',
                'camera_ready',
                'conference',
            ])
            ->orderBy('date')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('conference_id');
        $nextAction = null;
        $actionPriority = PHP_INT_MAX;
        foreach ($participants as $participant) {
            $payment = $payments->get($participant->id);
            $submissions = $participant->submissions->sortByDesc('created_at');
            $isPresenter = $participant->registrationType?->category === 'presenter';
            if ($participant->registration_status === 'pending') {
                if (!$isPresenter) {
                    if (!$payment) {
                        $candidate = [
                            'priority' => 10,
                            'type' => 'warning',
                            'icon' => 'bi-credit-card',
                            'title' => 'Payment Required',
                            'description' => 'Your conference registration is waiting for payment.',
                            'button' => 'Submit Payment',
                            'route' => route('participant.payments.create'),
                        ];
                        if ($candidate['priority'] < $actionPriority) {
                            $nextAction = $candidate;
                            $actionPriority = $candidate['priority'];
                        }
                    } elseif ($payment->status === 'rejected') {
                        $candidate = [
                            'priority' => 1,
                            'type' => 'danger',
                            'icon' => 'bi-exclamation-circle',
                            'title' => 'Payment Rejected',
                            'description' => 'Your payment requires attention. Please review your payment and submit a new proof.',
                            'button' => 'Review Payment',
                            'route' => route('participant.payments.index'),
                        ];
                        if ($candidate['priority'] < $actionPriority) {
                            $nextAction = $candidate;
                            $actionPriority = $candidate['priority'];
                        }
                    } elseif ($payment->status === 'pending') {
                        $candidate = [
                            'priority' => 5,
                            'type' => 'warning',
                            'icon' => 'bi-hourglass-split',
                            'title' => 'Payment Verification',
                            'description' => 'Your payment proof has been submitted and is waiting for verification.',
                            'button' => 'View Payment',
                            'route' => route('participant.payments.index'),
                        ];
                        if ($candidate['priority'] < $actionPriority) {
                            $nextAction = $candidate;
                            $actionPriority = $candidate['priority'];
                        }
                    }
                    continue;
                }
                if ($payment) {
                    if ($payment->status === 'rejected') {
                        $candidate = [
                            'priority' => 1,
                            'type' => 'danger',
                            'icon' => 'bi-exclamation-circle',
                            'title' => 'Payment Rejected',
                            'description' => 'Your payment requires attention. Please review your payment and submit a new proof.',
                            'button' => 'Review Payment',
                            'route' => route('participant.payments.index'),
                        ];
                        if ($candidate['priority'] < $actionPriority) {
                            $nextAction = $candidate;
                            $actionPriority = $candidate['priority'];
                        }
                        continue;
                    }
                    if ($payment->status === 'pending') {
                        $candidate = [
                            'priority' => 5,
                            'type' => 'warning',
                            'icon' => 'bi-hourglass-split',
                            'title' => 'Payment Verification',
                            'description' => 'Your payment proof has been submitted and is waiting for verification.',
                            'button' => 'View Payment',
                            'route' => route('participant.payments.index'),
                        ];
                        if ($candidate['priority'] < $actionPriority) {
                            $nextAction = $candidate;
                            $actionPriority = $candidate['priority'];
                        }
                        continue;
                    }
                }
                if ($submissions->isEmpty()) {
                    if (
                        $participant->conference?->setting?->submission_enabled
                        && !$participant->conference?->setting?->maintenance_mode
                    ) {
                        $candidate = [
                            'priority' => 20,
                            'type' => 'success',
                            'icon' => 'bi-file-earmark-plus',
                            'title' => 'Submit Your Paper',
                            'description' => 'You can submit your paper for review before completing presenter payment.',
                            'button' => 'Submit Paper',
                            'route' => route('participant.submissions.create'),
                        ];
                    } else {
                        $candidate = [
                            'priority' => 90,
                            'type' => 'info',
                            'icon' => 'bi-hourglass',
                            'title' => 'Waiting for Submission',
                            'description' => 'Submission is currently not open.',
                            'button' => null,
                            'route' => null,
                        ];
                    }
                    if ($candidate['priority'] < $actionPriority) {
                        $nextAction = $candidate;
                        $actionPriority = $candidate['priority'];
                    }
                    continue;
                }
            }
            if ($participant->registration_status !== 'confirmed' && !$isPresenter) {
                continue;
            }
            if ($submissions->isEmpty()) {
                if (
                    $participant->conference?->setting?->submission_enabled &&
                    !$participant->conference?->setting?->maintenance_mode
                ) {
                    $candidate = [
                        'priority' => 20,
                        'type' => 'success',
                        'icon' => 'bi-file-earmark-plus',
                        'title' => 'Submit Your Paper',
                        'description' => 'Your registration is confirmed. You can now submit your paper.',
                        'button' => 'Submit Paper',
                        'route' => route('participant.submissions.create'),
                    ];
                } else {
                    $candidate = [
                        'priority' => 90,
                        'type' => 'info',
                        'icon' => 'bi-hourglass',
                        'title' => 'Waiting for Submission',
                        'description' => 'Your registration is confirmed. Submission is currently not open.',
                        'button' => null,
                        'route' => null,
                    ];
                }
                if ($candidate['priority'] < $actionPriority) {
                    $nextAction = $candidate;
                    $actionPriority = $candidate['priority'];
                }
                continue;
            }
            foreach ($submissions as $submission) {
                $candidate = null;
                switch ($submission->status) {
                    case 'revision':
                        $candidate = [
                            'priority' => 2,
                            'type' => 'warning',
                            'icon' => 'bi-arrow-repeat',
                            'title' => 'Revision Required',
                            'description' => 'The reviewers have requested changes to your paper.',
                            'button' => 'Upload Revision',
                            'route' => route('participant.submissions.revision', $submission),
                        ];
                        break;
                    case 'accepted':
                        $presentationComplete =
                            !empty($submission->presentation_type)
                            && !empty($submission->presentation_mode)
                            && !empty($submission->presenter_author_id);
                        if (!$presentationComplete) {
                            $candidate = [
                                'priority' => 3,
                                'type' => 'success',
                                'icon' => 'bi-easel',
                                'title' => 'Complete Presentation Details',
                                'description' => 'Your paper has been accepted. Please complete your presentation details before proceeding to payment.',
                                'button' => 'Presentation Details',
                                'route' => route(
                                    'participant.submissions.presentation.edit',
                                    $submission
                                ),
                            ];
                        } elseif (!$payment) {
                            $candidate = [
                                'priority' => 4,
                                'type' => 'warning',
                                'icon' => 'bi-credit-card',
                                'title' => 'Payment Required',
                                'description' => 'Your presentation details are complete. Please complete the payment for your accepted paper.',
                                'button' => 'Submit Payment',
                                'route' => route(
                                    'participant.payments.create'
                                ),
                            ];
                        } elseif ($payment->status === 'rejected') {
                            $candidate = [
                                'priority' => 2,
                                'type' => 'danger',
                                'icon' => 'bi-exclamation-circle',
                                'title' => 'Payment Rejected',
                                'description' => 'Your payment was rejected. Please review the payment information and submit a new proof.',
                                'button' => 'Review Payment',
                                'route' => route(
                                    'participant.payments.index'
                                ),
                            ];
                        } elseif ($payment->status === 'pending') {
                            $candidate = [
                                'priority' => 4,
                                'type' => 'warning',
                                'icon' => 'bi-hourglass-split',
                                'title' => 'Payment Verification',
                                'description' => 'Your payment proof has been submitted and is waiting for administrator verification.',
                                'button' => 'View Payment',
                                'route' => route(
                                    'participant.payments.index'
                                ),
                            ];
                        } elseif ($payment->status === 'verified') {
                            $candidate = [
                                'priority' => 5,
                                'type' => 'info',
                                'icon' => 'bi-file-earmark-check',
                                'title' => 'Submit Camera-Ready Paper',
                                'description' => 'Your paper, presentation details, and payment are complete. Please upload the final camera-ready version of your paper.',
                                'button' => 'Upload Camera Ready',
                                'route' => route(
                                    'participant.submissions.camera-ready',
                                    $submission
                                ),
                            ];
                        }
                        break;
                    case 'under_review':
                        $candidate = [
                            'priority' => 50,
                            'type' => 'info',
                            'icon' => 'bi-search',
                            'title' => 'Paper Under Review',
                            'description' => 'Your paper is currently being reviewed. No action is required from you.',
                            'button' => 'View Submission',
                            'route' => route('participant.submissions.show', $submission),
                        ];
                        break;
                    case 'submitted':
                        $candidate = [
                            'priority' => 60,
                            'type' => 'info',
                            'icon' => 'bi-clock',
                            'title' => 'Submission Received',
                            'description' => 'Your paper has been submitted successfully and is waiting for the review process.',
                            'button' => 'View Submission',
                            'route' => route('participant.submissions.show', $submission),
                        ];
                        break;
                    case 'camera_ready':
                        $candidate = [
                            'priority' => 40,
                            'type' => 'info',
                            'icon' => 'bi-hourglass-split',
                            'title' => 'Camera Ready Under Review',
                            'description' => 'Your camera-ready paper has been submitted and is waiting for approval.',
                            'button' => 'View Submission',
                            'route' => route('participant.submissions.show', $submission),
                        ];
                        break;
                }
                if ($candidate && $candidate['priority'] < $actionPriority) {
                    $nextAction = $candidate;
                    $actionPriority = $candidate['priority'];
                }
            }
        }
        if (!$nextAction) {
            $publishedSubmission = $participants
                ->flatMap(fn($participant) => $participant->submissions)
                ->where('status', 'published')
                ->sortByDesc('created_at')
                ->first();
            if ($publishedSubmission) {
                $nextAction = [
                    'priority' => 100,
                    'type' => 'dark',
                    'icon' => 'bi-award',
                    'title' => 'Paper Published',
                    'description' => 'Congratulations! Your paper has been published. Your certificate is available.',
                    'button' => 'View Certificates',
                    'route' => route('participant.certificates.index'),
                ];
            }
        }
        return view('participant.dashboard', compact(
            'participants',
            'payments',
            'importantDates',
            'nextAction',
        ));
    }
}
