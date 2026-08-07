@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-2 rounded-0" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert">
        </button>
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-2 rounded-0" role="alert">
        <i class="bi bi-x-circle-fill me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert">
        </button>
    </div>
@endif
@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show mb-2 rounded-0" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert">
        </button>
    </div>
@endif
@if (session('info'))
    <div class="alert alert-info alert-dismissible fade show mb-2 rounded-0" role="alert">
        <i class="bi bi-info-circle-fill me-2"></i>
        {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert">
        </button>
    </div>
@endif
