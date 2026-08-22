<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>
        Submission Status - ICON 2026
    </title>
</head>

<body>
    <h2>
        Submission Status Update
    </h2>
    <p>
        Dear
        {{ $submission->participant?->full_name ?? 'Participant' }},
    </p>
    <p>
        Your submission status has been updated.
    </p>
    <p>
        <strong>
            Title:
        </strong>
        {{ $submission->title }}
    </p>
    <p>
        <strong>
            Submission Code:
        </strong>
        {{ $submission->submission_code }}
    </p>
    <p>
        <strong>
            Current Status:
        </strong>
        {{ ucwords(str_replace('_', ' ', $submission->status)) }}
    </p>
    <p>
        {{ $statusMessage }}
    </p>
    <p>
        Please log in to the ICON 2026 Participant Portal
        for further information.
    </p>

</body>

</html>
