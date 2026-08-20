<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Payment Verified</title>
</head>

<body>

    <h2>Payment Verified</h2>

    <p>
        Dear {{ $payment->participant->full_name }},
    </p>

    <p>
        Your payment for
        <strong>
            {{ $payment->participant->conference->name }}
        </strong>
        has been verified successfully.
    </p>

    <p>
        Registration Number:
        <strong>
            {{ $payment->participant->registration_number }}
        </strong>
    </p>

    <p>
        Payment Code:
        <strong>
            {{ $payment->payment_code }}
        </strong>
    </p>

    <p>
        Your registration is now confirmed.
    </p>

    <p>
        Thank you.
    </p>

</body>

</html>
