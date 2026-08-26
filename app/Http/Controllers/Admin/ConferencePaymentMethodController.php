<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conference;
use App\Models\ConferencePaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ConferencePaymentMethodController extends Controller
{
    public function index(Conference $conference)
    {
        $paymentMethods = $conference
            ->paymentMethods()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15);

        return view(
            'admin.conference-payment-methods.index',
            compact(
                'conference',
                'paymentMethods'
            )
        );
    }

    public function create(Conference $conference)
    {
        return view(
            'admin.conference-payment-methods.create',
            compact('conference')
        );
    }

    public function store(
        Request $request,
        Conference $conference
    ) {
        $validated = $this->validateData($request);

        if ($request->hasFile('qr_code_file')) {
            $validated['qr_code_file'] = $request
                ->file('qr_code_file')
                ->store(
                    'conference-payment-methods/qr-codes',
                    'public'
                );
        }

        $conference->paymentMethods()->create(
            $validated
        );

        return redirect()
            ->route(
                'admin.conferences.payment-methods.index',
                $conference
            )
            ->with(
                'success',
                'Payment method created successfully.'
            );
    }

    public function edit(
        Conference $conference,
        ConferencePaymentMethod $paymentMethod
    ) {
        // dd($paymentMethod->type);
        return view(
            'admin.conference-payment-methods.edit',
            compact(
                'conference',
                'paymentMethod'
            )
        );
    }

    public function update(
        Request $request,
        Conference $conference,
        ConferencePaymentMethod $paymentMethod
    ) {
        $validated = $this->validateData(
            $request,
            $paymentMethod
        );

        if ($request->hasFile('qr_code_file')) {
            if ($paymentMethod->qr_code_file) {
                Storage::disk('public')->delete(
                    $paymentMethod->qr_code_file
                );
            }

            $validated['qr_code_file'] = $request
                ->file('qr_code_file')
                ->store(
                    'conference-payment-methods/qr-codes',
                    'public'
                );
        }

        $paymentMethod->update(
            $validated
        );

        return redirect()
            ->route(
                'admin.conferences.payment-methods.index',
                $conference
            )
            ->with(
                'success',
                'Payment method updated successfully.'
            );
    }

    public function destroy(
        Conference $conference,
        ConferencePaymentMethod $paymentMethod
    ) {
        if ($paymentMethod->qr_code_file) {
            Storage::disk('public')->delete(
                $paymentMethod->qr_code_file
            );
        }

        $paymentMethod->delete();

        return redirect()
            ->route(
                'admin.conferences.payment-methods.index',
                $conference
            )
            ->with(
                'success',
                'Payment method deleted successfully.'
            );
    }

    private function validateData(
        Request $request,
        ?ConferencePaymentMethod $paymentMethod = null
    ): array {
        return $request->validate([
            'type' => [
                'required',
                Rule::in([
                    'bank_transfer',
                    'qris',
                    'online_payment',
                    'other',
                ]),
            ],

            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'provider' => [
                'nullable',
                'string',
                'max:100',
            ],

            'account_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'account_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'currency' => [
                'required',
                'string',
                'size:3',
            ],

            'instructions' => [
                'nullable',
                'string',
            ],

            'qr_code_file' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'sort_order' => [
                'required',
                'integer',
                'min:0',
                'max:255',
            ],
        ]);
    }
}
