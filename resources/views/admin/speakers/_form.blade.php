<div class="card-body">
    <div class="row">
        <div class="col-md-3 mb-2">
            <label class="form-label">
                Speaker Photo
            </label>
            <div class="mb-2">
                @if (isset($speaker) && $speaker->photo)
                    <img src="{{ asset('storage/' . $speaker->photo) }}" alt="{{ $speaker->name }}" id="photo-preview"
                        class="img-thumbnail rounded-0 d-block" style="width: 180px; height: 180px; object-fit: cover;">
                @else
                    <div id="photo-placeholder"
                        class="border rounded-0 d-flex align-items-center justify-content-center bg-light"
                        style="width: 180px; height: 180px;">
                        <div class="text-center text-muted">
                            <i class="bi bi-person display-4"></i>
                            <div class="small">
                                No Photo
                            </div>
                        </div>
                    </div>
                    <img src="" alt="Preview" id="photo-preview" class="img-thumbnail d-none rounded-0"
                        style="width: 180px; height: 180px; object-fit: cover;">
                @endif
            </div>
            <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/webp"
                class="form-control @error('photo') is-invalid @enderror rounded-0">
            <div class="form-text">
                JPG, PNG, or WebP. Maximum 2 MB.
            </div>
            @error('photo')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-9 mb-2">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label class="form-label">
                        Conference
                        <span class="text-danger">*</span>
                    </label>
                    <select name="conference_id"
                        class="form-select @error('conference_id') is-invalid @enderror rounded-0">
                        <option value="">
                            Select Conference
                        </option>
                        @foreach ($conferences as $conference)
                            <option value="{{ $conference->id }}" @selected(old('conference_id', $speaker->conference_id ?? '') == $conference->id)>

                                {{ $conference->short_name }}
                                ({{ $conference->year }})
                            </option>
                        @endforeach
                    </select>
                    @error('conference_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label">
                        Speaker Name
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $speaker->name ?? '') }}"
                        class="form-control @error('name') is-invalid @enderror rounded-0"
                        placeholder="e.g. Dr. John Doe">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label">
                        Academic Title
                    </label>
                    <input type="text" name="title" value="{{ old('title', $speaker->title ?? '') }}"
                        class="form-control @error('title') is-invalid @enderror rounded-0"
                        placeholder="e.g. Ph.D., M.T.">
                    @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label">
                        Position
                    </label>
                    <input type="text" name="position" value="{{ old('position', $speaker->position ?? '') }}"
                        class="form-control @error('position') is-invalid @enderror rounded-0"
                        placeholder="e.g. Professor">
                    @error('position')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-12 mb-2">
                    <label class="form-label">
                        Institution
                    </label>
                    <input type="text" name="institution"
                        value="{{ old('institution', $speaker->institution ?? '') }}"
                        class="form-control @error('institution') is-invalid @enderror rounded-0"
                        placeholder="e.g. Universitas Bhamada">
                    @error('institution')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card-body border-top">
    <div class="row">
        <div class="col-12 mb-2">
            <label class="form-label">
                Biography
            </label>
            <textarea name="bio" rows="5" class="form-control @error('bio') is-invalid @enderror rounded-0"
                placeholder="Write a short biography of the speaker...">{{ old('bio', $speaker->bio ?? '') }}</textarea>
            @error('bio')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-4 mb-2">
            <label class="form-label">
                Email
            </label>
            <input type="email" name="email" value="{{ old('email', $speaker->email ?? '') }}"
                class="form-control @error('email') is-invalid @enderror rounded-0" placeholder="speaker@example.com">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-4 mb-2">
            <label class="form-label">
                LinkedIn
            </label>
            <input type="url" name="linkedin" value="{{ old('linkedin', $speaker->linkedin ?? '') }}"
                class="form-control @error('linkedin') is-invalid @enderror rounded-0"
                placeholder="https://linkedin.com/in/...">
            @error('linkedin')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-4 mb-2">
            <label class="form-label">
                Website
            </label>
            <input type="url" name="website" value="{{ old('website', $speaker->website ?? '') }}"
                class="form-control @error('website') is-invalid @enderror rounded-0"
                placeholder="https://example.com">
            @error('website')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-3 mb-2">
            <label class="form-label">
                Sort Order
            </label>
            <input type="number" name="sort_order" min="0"
                value="{{ old('sort_order', $speaker->sort_order ?? 0) }}"
                class="form-control @error('sort_order') is-invalid @enderror rounded-0">
            @error('sort_order')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-3 mb-2">
            <label class="form-label d-block">
                Status
            </label>
            <div class="form-check form-switch mt-2">
                <input type="hidden" name="is_active" value="0">
                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                    @checked(old('is_active', $speaker->is_active ?? true))>
                <label class="form-check-label">
                    Active
                </label>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const photoInput = document.getElementById('photo');
            const photoPreview = document.getElementById('photo-preview');
            const photoPlaceholder = document.getElementById('photo-placeholder');

            if (!photoInput) {
                return;
            }

            photoInput.addEventListener('change', function(event) {

                const file = event.target.files[0];

                if (!file) {
                    return;
                }

                const reader = new FileReader();

                reader.onload = function(e) {

                    photoPreview.src = e.target.result;

                    photoPreview.classList.remove('d-none');

                    if (photoPlaceholder) {
                        photoPlaceholder.classList.add('d-none');
                    }

                };

                reader.readAsDataURL(file);

            });

        });
    </script>
@endpush
