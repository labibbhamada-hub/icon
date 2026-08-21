<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Submission Status</title>
</head>

<body>
    <h2>Submission Status Update</h2>
    <p>
        Dear {{ $submission->participant->full_name }},
    </p>
    <p>
        Your submission:
    </p>
    <p>
        <strong>
            {{ $submission->title }}
        </strong>
    </p>
    <p>
        Submission Code:
        <strong>
            {{ $submission->submission_code }}
        </strong>
    </p>
    <p>
        {{ $statusMessage }}
    </p>
    <p>
        Please log in to the ICON 2026 Participant Portal for more details.
    </p>
</body>

</html>
