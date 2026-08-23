<?php

namespace App\Http\Controllers;

use App\Models\Submission;

class LoaVerificationController extends Controller
{
    public function show(string $submissionCode)
    {
        $submission = Submission::with([
            'participant',
            'participant.user',
            'conference',
            'topic',
            'authors',
        ])
            ->where('submission_code', $submissionCode)
            ->first();
        if (!$submission || !in_array($submission->status, [
            'accepted',
            'camera_ready',
            'published',
        ], true)) {
            return view('loa.verify', [
                'submission' => null,
            ]);
        }
        $loaNumber = 'LOA/ICON/' .
            ($submission->conference?->year ?? now()->year) .
            '/' .
            str_pad(
                $submission->id,
                4,
                '0',
                STR_PAD_LEFT
            );
        return view('loa.verify', compact(
            'submission',
            'loaNumber'
        ));
    }
}
