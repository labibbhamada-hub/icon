<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateVerificationController extends Controller
{
    public function index(Request $request)
    {
        $certificate = null;
        $searched = false;

        if ($request->filled('certificate_number')) {

            $searched = true;

            $certificate = Certificate::with([
                'participant',
                'conference',
            ])
                ->where(
                    'certificate_number',
                    strtoupper(
                        trim(
                            $request->certificate_number
                        )
                    )
                )
                ->whereNotNull('issued_at')
                ->first();
        }

        return view(
            'certificates.verify',
            compact(
                'certificate',
                'searched'
            )
        );
    }
}
