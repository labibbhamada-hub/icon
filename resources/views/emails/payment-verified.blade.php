<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <title>
        Payment Verified - ICON 2026
    </title>
</head>

<body>

    <h2>
        Payment Verified
    </h2>

    <p>
        Dear
        {{ $payment->participant?->full_name ?? 'Participant' }},
    </p>

    <p>
        Your payment has been successfully verified.
    </p>

    <p>
        <strong>Payment Code:</strong>
        {{ $payment->payment_code }}
    </p>

    <p>
        <strong>Amount:</strong>
        Rp {{ number_format($payment->amount, 0, ',', '.') }}
    </p>

    <p>
        <strong>Conference:</strong>
        {{ $payment->participant?->conference?->name ?? 'ICON 2026' }}
    </p>

    <p>
        Your registration is now confirmed.
    </p>

    <p>
        Thank you for registering for ICON 2026.
    </p>

</body>

</html>
