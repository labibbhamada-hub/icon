<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <style>
        @page {
            margin: 0;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
        }

        .certificate {
            margin: 12mm;
            border: 3px solid #1f4e79;
            padding: 8mm;
        }

        .inner {
            border: 1px solid #c9a227;
            padding: 12mm;
            text-align: center;
        }

        .organization {
            font-size: 15px;
            font-weight: bold;
            color: #1f4e79;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .year {
            margin-top: 2mm;
            font-size: 9px;
            color: #777;
        }

        .title {
            margin-top: 10mm;
            font-size: 26px;
            font-weight: bold;
            letter-spacing: 4px;
            color: #1f4e79;
            text-transform: uppercase;
        }

        .presented {
            margin-top: 8mm;
            font-size: 10px;
            color: #777;
        }

        .recipient {
            margin-top: 4mm;
            font-size: 22px;
            font-weight: bold;
        }

        .description {
            margin-top: 6mm;
            font-size: 10px;
            color: #444;
            line-height: 1.5;
        }

        .conference {
            margin-top: 3mm;
            font-size: 13px;
            font-weight: bold;
            color: #1f4e79;
        }

        .paper {
            margin-top: 5mm;
            font-size: 9px;
            color: #555;
        }

        .paper-title {
            margin-top: 2mm;
            font-size: 10px;
            font-weight: bold;
            line-height: 1.4;
        }

        .meta {
            margin-top: 6mm;
            font-size: 8px;
            color: #777;
            line-height: 1.6;
        }

        .signature {
            margin-top: 9mm;
            width: 60mm;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin-bottom: 2mm;
        }

        .signature-name {
            font-size: 9px;
            font-weight: bold;
        }

        .signature-title {
            margin-top: 1mm;
            font-size: 8px;
            color: #777;
        }
    </style>

</head>

<body>

    <div class="certificate">
        <div class="inner">
            <div class="organization">
                {{ $certificate->conference?->name }}
            </div>
            <div class="year">
                {{ $certificate->conference?->year }}
            </div>
            <div class="title">
                CERTIFICATE OF {{ strtoupper($certificate->type) }}
            </div>
            <div class="presented">
                This certificate is proudly presented to
            </div>
            <div class="recipient">
                {{ $certificate->participant?->full_name }}
            </div>
            <div class="description">
                @if ($certificate->type === 'reviewer')
                    In recognition of valuable contribution as a reviewer
                    in the conference.
                @elseif ($certificate->type === 'presenter')
                    In recognition of participation and contribution
                    as a presenter in the conference.
                @elseif ($certificate->type === 'speaker')
                    In recognition of valuable contribution as a speaker
                    in the conference.
                @else
                    In recognition of participation and contribution
                    to the conference.
                @endif
            </div>
            <div class="conference">
                {{ $certificate->conference?->name }}
            </div>
            @if ($certificate->submission && in_array($certificate->type, ['presenter'], true))
                <div class="paper">
                    For the paper entitled:
                    <div class="paper-title">
                        "{{ $certificate->submission->title }}"
                    </div>
                </div>
            @endif
            <div class="meta">
                Certificate Number:
                <strong>
                    {{ $certificate->certificate_number }}
                </strong>
                <br>
                Issued:
                {{ $certificate->issued_at?->format('d F Y') }}
                <br>
                Verify:
                <strong>
                    {{ url('/certificate/verify?certificate_number=' . urlencode($certificate->certificate_number)) }}
                </strong>
            </div>
            <div class="signature">
                <div class="signature-line"></div>
                <div class="signature-name">
                    {{ $certificate->conference?->configuration?->chair_name ?? 'Conference Chair' }}
                </div>
                @if ($certificate->conference?->configuration?->chair_title)
                    <div class="signature-title">
                        {{ $certificate->conference->configuration->chair_title }}
                    </div>
                @endif
                <div class="signature-title">
                    {{ $certificate->conference?->short_name }}
                    {{ $certificate->conference?->year }}
                </div>
            </div>
        </div>
    </div>
</body>

</html>
