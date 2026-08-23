<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Letter of Acceptance - {{ $submission->submission_code }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 portrait;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            background: #ffffff;
        }

        body {
            color: #172033;
            font-family: DejaVu Sans, sans-serif;
            font-size: 10.5px;
            line-height: 1.45;
        }

        .page {
            width: auto;
            min-height: 0;
            margin: 0;
            padding: 13mm 16mm 10mm;
        }

        .top-line {
            width: 100%;
            height: 5px;
            background: #123d7a;
            margin-bottom: 15px;
        }

        .header {
            width: 100%;
            text-align: center;
            margin-bottom: 14px;
        }

        .header-logo {
            max-width: 75px;
            max-height: 55px;
            margin-bottom: 6px;
        }

        .conference-name {
            max-width: 100%;
            margin: 0 auto;
            font-size: 17px;
            line-height: 1.25;
            font-weight: bold;
            color: #123d7a;
        }

        .conference-year {
            font-size: 9.5px;
            color: #6b7280;
            margin-top: 2px;
        }

        .document-title {
            margin-top: 12px;
            font-size: 19px;
            font-weight: bold;
            letter-spacing: 0.8px;
            color: #172033;
        }

        .document-subtitle {
            margin-top: 2px;
            font-size: 9.5px;
            color: #6b7280;
        }

        .meta-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .meta-table td {
            padding: 6px 8px;
            border: 1px solid #d9e0ea;
            vertical-align: top;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        .meta-label {
            width: 22%;
            background: #f4f7fb;
            font-weight: bold;
            color: #475569;
        }

        .meta-value {
            width: 78%;
            font-weight: 600;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #123d7a;
            margin-top: 12px;
            margin-bottom: 5px;
        }

        .paper-title {
            width: 100%;
            padding: 8px 10px;
            background: #f6f8fb;
            border-left: 4px solid #123d7a;
            font-weight: bold;
            font-size: 11px;
            line-height: 1.45;
            margin-bottom: 8px;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        .content {
            width: 100%;
            text-align: justify;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        .content p {
            margin-top: 0;
            margin-bottom: 8px;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        .authors {
            margin: 5px 0 0;
            padding-left: 18px;
        }

        .authors li {
            margin-bottom: 2px;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        .dates {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .dates th {
            background: #123d7a;
            color: #ffffff;
            padding: 6px 8px;
            text-align: left;
            font-size: 9.5px;
        }

        .dates th:first-child {
            width: 58%;
        }

        .dates th:last-child {
            width: 42%;
        }

        .dates td {
            border: 1px solid #d9e0ea;
            padding: 5px 8px;
            font-size: 9.5px;
            vertical-align: top;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        .dates tr:nth-child(even) td {
            background: #f8fafc;
        }

        .closing {
            margin-top: 12px;
            margin-bottom: 0;
            text-align: justify;
        }

        .signature {
            margin-top: 12px !important;
            margin-bottom: 0 !important;
        }

        .signature-name {
            margin-top: 16px;
            font-weight: bold;
            font-size: 10.5px;
        }

        .signature-title {
            color: #475569;
            font-size: 10px;
        }

        .verification {
            text-align: right;
            margin-top: 12px;
        }

        .qr-code {
            width: 75px;
            height: 75px;
        }

        .verification-text {
            font-size: 7.5px;
            color: #64748b;
            margin-top: 2px;
        }

        .verification-number {
            font-size: 7.5px;
            font-weight: bold;
            color: #123d7a;
            margin-top: 1px;
        }

        .footer {
            margin-top: 12mm;
            border-top: 1px solid #d9e0ea;
            padding-top: 5px;
            font-size: 7.5px;
            color: #64748b;
        }

        .footer-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        .footer-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="top-line"></div>
        <div class="header">
            @if ($submission->conference?->configuration?->logo)
                @php
                    $logoPath = $submission->conference->configuration->logo;
                    $logoData = null;
                    $logoMime = null;
                    foreach (['local', 'public'] as $disk) {
                        if (\Illuminate\Support\Facades\Storage::disk($disk)->exists($logoPath)) {
                            $logoData = base64_encode(\Illuminate\Support\Facades\Storage::disk($disk)->get($logoPath));
                            $logoMime = \Illuminate\Support\Facades\Storage::disk($disk)->mimeType($logoPath);
                            break;
                        }
                    }
                @endphp
                @if ($logoData && $logoMime)
                    <img src="data:{{ $logoMime }};base64,{{ $logoData }}" alt="Conference Logo"
                        class="header-logo">
                @endif
            @endif
            <h1 class="conference-name">
                {{ $submission->conference?->name ?? 'ICON 2026' }}
            </h1>
            @if ($submission->conference?->year)
                <div class="conference-year">
                    {{ $submission->conference->year }}
                </div>
            @endif
            <div class="document-title">
                LETTER OF ACCEPTANCE
            </div>
            <div class="document-subtitle">
                Official Acceptance Letter
            </div>
        </div>
        <table class="meta-table">
            <tr>
                <td class="meta-label">
                    Submission Code
                </td>
                <td class="meta-value">
                    {{ $submission->submission_code }}
                </td>
            </tr>
            <tr>
                <td class="meta-label">
                    LOA Number
                </td>
                <td class="meta-value">
                    {{ $loaNumber }}
                </td>
            </tr>
            <tr>
                <td class="meta-label">
                    Status
                </td>
                <td class="meta-value">
                    Accepted
                </td>
            </tr>
            <tr>
                <td class="meta-label">
                    Topic
                </td>
                <td class="meta-value">
                    {{ $submission->topic?->name ?? '-' }}
                </td>
            </tr>
            <tr>
                <td class="meta-label">
                    Acceptance Date
                </td>
                <td class="meta-value">
                    {{ optional($submission->updated_at)->format('d F Y') }}
                </td>
            </tr>
        </table>
        <div class="content">
            <p>
                Dear Author(s),
            </p>
            <p>
                We are pleased to inform you that the following paper has been accepted for presentation at
                <strong>{{ $submission->conference?->name ?? 'ICON 2026' }}</strong>.
            </p>
            <div class="section-title">
                Paper Title
            </div>
            <div class="paper-title">
                {{ $submission->title }}
            </div>
            <p>
                The paper has successfully passed the review process and is eligible to proceed to the next stage
                of the conference publication process.
            </p>
            <div class="section-title">
                Author(s)
            </div>
            @if ($submission->authors->isNotEmpty())
                <ol class="authors">
                    @foreach ($submission->authors as $author)
                        <li>
                            <strong>{{ $author->name }}</strong>
                            @if ($author->institution)
                                - {{ $author->institution }}
                            @endif
                        </li>
                    @endforeach
                </ol>
            @else
                <p>
                    {{ $participant->user?->name ?? 'Author' }}
                </p>
            @endif
            @if ($importantDates->isNotEmpty())
                <div class="section-title">
                    Important Dates
                </div>
                <table class="dates">
                    <thead>
                        <tr>
                            <th style="width: 60%;">
                                Event
                            </th>
                            <th style="width: 40%;">
                                Date
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($importantDates as $importantDate)
                            <tr>
                                <td>
                                    {{ $importantDate->title }}
                                </td>
                                <td>
                                    {{ $importantDate->date->format('d F Y') }}
                                    @if ($importantDate->end_date)
                                        - {{ $importantDate->end_date->format('d F Y') }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            <p class="closing">
                Please proceed with the required next steps through the ICON 2026 Participant Portal.
                We look forward to your valuable contribution and participation in the conference.
            </p>
            <p class="signature">
                Sincerely,
            </p>
            <div class="signature-name">
                {{ $submission->conference?->configuration?->chair_name ?? 'Conference Chair' }}
            </div>
            @if ($submission->conference?->configuration?->chair_title)
                <div class="signature-title">
                    {{ $submission->conference->configuration->chair_title }}
                </div>
            @endif
            <div class="signature-title">
                {{ $submission->conference?->name ?? 'ICON 2026' }}
            </div>
        </div>
        <div class="verification">
            <img src="{{ $qrCodeDataUri }}" alt="LOA Verification QR Code" class="qr-code">
            <div class="verification-text">
                Scan to verify this Letter of Acceptance
            </div>
            <div class="verification-number">
                {{ $loaNumber }}
            </div>
        </div>
        <div class="footer">
            <table class="footer-table">
                <tr>
                    <td>
                        {{ $submission->conference?->short_name ?? 'ICON' }}
                        {{ $submission->conference?->year ?? date('Y') }}
                    </td>
                    <td class="footer-right">
                        Submission: {{ $submission->submission_code }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
