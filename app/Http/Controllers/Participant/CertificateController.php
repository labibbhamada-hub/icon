<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::with([
            'conference',
            'submission',
        ])
            ->whereHas(
                'participant',
                function ($query) {
                    $query->where(
                        'user_id',
                        Auth::id()
                    );
                }
            )
            ->latest()
            ->get();

        return view(
            'participant.certificates.index',
            compact('certificates')
        );
    }

    public function show(Certificate $certificate)
    {
        $owned = $certificate->participant()
            ->where(
                'user_id',
                Auth::id()
            )
            ->exists();

        abort_unless($owned, 403);

        $certificate->load([
            'participant',
            'conference',
            'submission',
        ]);

        return view(
            'participant.certificates.show',
            compact('certificate')
        );
    }

    public function download(Certificate $certificate)
    {
        $owned = $certificate->participant()
            ->where(
                'user_id',
                Auth::id()
            )
            ->exists();

        abort_unless($owned, 403);

        abort_unless(
            $certificate->file_path,
            404
        );

        abort_unless(
            Storage::disk('public')
                ->exists(
                    $certificate->file_path
                ),
            404
        );

        return Storage::disk('public')
            ->download(
                $certificate->file_path,
                $certificate->certificate_number . '.pdf'
            );
    }
}
