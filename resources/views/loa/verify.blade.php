<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOA Verification</title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background: #f4f7fb;
            color: #172033;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .card {
            width: 100%;
            max-width: 720px;
            background: #ffffff;
            border: 1px solid #d9e0ea;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .header {
            background: #123d7a;
            color: #ffffff;
            text-align: center;
            padding: 28px 24px;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
        }

        .header p {
            margin: 6px 0 0;
            opacity: 0.9;
        }

        .body {
            padding: 28px;
        }

        .status {
            padding: 14px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .valid {
            background: #ecfdf3;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .invalid {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .item {
            display: flex;
            gap: 16px;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .label {
            width: 170px;
            color: #64748b;
            font-weight: 600;
        }

        .value {
            flex: 1;
            font-weight: 600;
        }

        .footer {
            text-align: center;
            padding: 18px 24px;
            color: #64748b;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="header">
            <h1>LOA Verification</h1>
            <p>Official Letter of Acceptance Verification</p>
        </div>
        <div class="body">
            @if (!$submission)
                <div class="status invalid">
                    <strong>Invalid LOA</strong>
                    <div class="mt-1">
                        The Letter of Acceptance could not be verified in our system.
                    </div>
                </div>
            @else
                <div class="status valid">
                    <strong>Valid Letter of Acceptance ✓</strong>
                    <div class="mt-1">
                        This Letter of Acceptance is registered and verified in our system.
                    </div>
                </div>
                <div class="item">
                    <div class="label">
                        LOA Number
                    </div>
                    <div class="value">
                        {{ $loaNumber }}
                    </div>
                </div>
                <div class="item">
                    <div class="label">
                        Submission Code
                    </div>
                    <div class="value">
                        {{ $submission->submission_code }}
                    </div>
                </div>
                <div class="item">
                    <div class="label">
                        Paper Title
                    </div>
                    <div class="value">
                        {{ $submission->title }}
                    </div>
                </div>
                <div class="item">
                    <div class="label">
                        Author(s)
                    </div>
                    <div class="value">
                        @foreach ($submission->authors as $author)
                            <div>{{ $author->name }}</div>
                        @endforeach
                    </div>
                </div>
                <div class="item">
                    <div class="label">
                        Conference
                    </div>
                    <div class="value">
                        {{ $submission->conference?->name ?? 'ICON 2026' }}
                    </div>
                </div>
                <div class="item">
                    <div class="label">
                        Status
                    </div>
                    <div class="value">
                        Accepted
                    </div>
                </div>
                <div class="item">
                    <div class="label">
                        Accepted On
                    </div>
                    <div class="value">
                        {{ optional($submission->updated_at)->format('d F Y') }}
                    </div>
                </div>
            @endif
        </div>
        <div class="footer">
            {{ $submission?->conference?->name ?? 'ICON 2026' }}
        </div>
    </div>
</body>

</html>
