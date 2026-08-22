<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Mail\PaymentVerifiedMail;
use App\Exports\PaymentsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with([
            'participant.conference',
            'verifier',
        ])
            ->latest()
            ->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load([
            'participant.conference',
            'participant.user',
            'verifier',
        ]);

        return view('admin.payments.show', compact('payment'));
    }

    public function verify(Payment $payment)
    {
        if ($payment->status === 'verified') {
            return back()
                ->with(
                    'error',
                    'This payment has already been verified.'
                );
        }

        DB::transaction(function () use ($payment) {
            $payment->update([
                'status' => 'verified',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
            ]);
            $payment->participant->update([
                'registration_status' => 'confirmed',
            ]);
            Mail::to(
                $payment->participant->email
            )->queue(
                new PaymentVerifiedMail($payment)
            );
        });

        $payment->load([
            'participant.conference',
            'participant.user',
        ]);

        if ($payment->participant->email) {
            Mail::to($payment->participant->email)
                ->queue(
                    new PaymentVerifiedMail($payment)
                );
        }

        return redirect()
            ->route('admin.payments.show', $payment)
            ->with(
                'success',
                'Payment verified successfully.'
            );
    }

    public function reject(Payment $payment)
    {
        if ($payment->status === 'verified') {
            return back()
                ->with(
                    'error',
                    'A verified payment cannot be rejected.'
                );
        }

        $payment->update([
            'status' => 'rejected',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.payments.show', $payment)
            ->with(
                'success',
                'Payment rejected successfully.'
            );
    }

    public function destroy(Payment $payment)
    {
        if ($payment->status === 'verified') {
            return back()
                ->with(
                    'error',
                    'A verified payment cannot be deleted.'
                );
        }

        if ($payment->proof_file) {
            Storage::disk('public')
                ->delete($payment->proof_file);
        }

        $payment->delete();

        return redirect()
            ->route('admin.payments.index')
            ->with(
                'success',
                'Payment deleted successfully.'
            );
    }

    public function export(Request $request)
    {
        $conferenceId = $request->integer('conference_id');

        $suffix = $conferenceId
            ? '-conference-' . $conferenceId
            : '-all';

        return Excel::download(
            new PaymentsExport($conferenceId),
            'payments' .
                $suffix .
                '-' .
                now()->format('Y-m-d') .
                '.xlsx'
        );
    }

    public function downloadProof(Payment $payment)
    {
        abort_unless(
            $payment->proof_file
                && Storage::disk('local')->exists(
                    $payment->proof_file
                ),
            404
        );

        return Storage::disk('local')->download(
            $payment->proof_file,
            basename($payment->proof_file)
        );
    }
}
