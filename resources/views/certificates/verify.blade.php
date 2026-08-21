<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        Certificate Verification - ICON
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="bi bi-patch-check-fill text-success" style="font-size: 4rem;">
                        </i>
                    </div>
                    <h2 class="fw-bold">
                        Certificate Verification
                    </h2>
                    <p class="text-muted mb-0">
                        Verify the authenticity of an ICON conference certificate.
                    </p>
                </div>
                <div class="card rounded-0 shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-search me-2"></i>
                            Verify Certificate
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('certificates.verify') }}" method="GET">
                            <label class="form-label">
                                Certificate Number
                            </label>
                            <div class="input-group">
                                <input type="text" name="certificate_number"
                                    value="{{ request('certificate_number') }}" class="form-control rounded-0"
                                    placeholder="Example: CERT-ICON-2026-ZYBBLM" autocomplete="off" required>
                                <button type="submit" class="btn btn-success rounded-0">
                                    <i class="bi bi-search me-1"></i>
                                    Verify
                                </button>
                            </div>
                            <div class="form-text">
                                Enter the certificate number exactly as shown
                                on the certificate.
                            </div>
                        </form>
                    </div>
                </div>
                @if ($searched)
                    @if ($certificate)
                        <div class="card rounded-0 shadow-sm mt-4 border-success">
                            <div class="card-header bg-success text-white rounded-0">
                                <h5 class="mb-0">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Valid Certificate
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-4">
                                    <span class="badge text-bg-success rounded-0 fs-6 px-3 py-2">
                                        VALID
                                    </span>
                                </div>
                                <table class="table table-borderless align-middle">
                                    <tbody>
                                        <tr>
                                            <th width="220">
                                                Certificate Number
                                            </th>
                                            <td>
                                                <strong>
                                                    {{ $certificate->certificate_number }}
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Name
                                            </th>
                                            <td>
                                                {{ $certificate->participant?->full_name ?? '—' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Registration Number
                                            </th>
                                            <td>
                                                {{ $certificate->participant?->registration_number ?? '—' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Conference
                                            </th>
                                            <td>
                                                {{ $certificate->conference?->name ?? '—' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Certificate Type
                                            </th>
                                            <td>
                                                <span class="badge text-bg-primary rounded-0">
                                                    {{ ucfirst($certificate->type) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Issued Date
                                            </th>
                                            <td>
                                                {{ $certificate->issued_at?->format('d F Y') ?? '—' }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="alert alert-success rounded-0 mb-0">
                                    <i class="bi bi-shield-check me-2"></i>
                                    This certificate is registered in the
                                    ICON conference system and is valid.
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card rounded-0 shadow-sm mt-4 border-danger">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-x-circle me-2"></i>
                                    Certificate Not Found
                                </h5>
                            </div>
                            <div class="card-body text-center py-5">
                                <i class="bi bi-file-earmark-x text-danger" style="font-size: 4rem;">
                                </i>
                                <h5 class="mt-3">
                                    Invalid Certificate
                                </h5>
                                <p class="text-muted mb-0">
                                    No valid certificate was found for the
                                    certificate number you entered.
                                </p>
                            </div>
                        </div>
                    @endif
                @endif
                <div class="text-center mt-4">
                    <small class="text-muted">
                        ICON Conference Management System
                    </small>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
