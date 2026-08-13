<div class="card-body">
    <div class="row">
        <div class="col-md-6 mb-2">
            <label class="form-label">
                Name
                <span class="text-danger">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}"
                class="form-control @error('name') is-invalid @enderror rounded-0" placeholder="Full name">
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-6 mb-2">
            <label class="form-label">
                Email
                <span class="text-danger">*</span>
            </label>
            <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}"
                class="form-control @error('email') is-invalid @enderror rounded-0" placeholder="user@example.com">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-6 mb-2">
            <label class="form-label">
                Password
                @if (!isset($user))
                    <span class="text-danger">*</span>
                @endif
            </label>
            <input type="password" name="password"
                class="form-control @error('password') is-invalid @enderror rounded-0"
                placeholder="{{ isset($user) ? 'Leave blank to keep current password' : 'Minimum 8 characters' }}">
            @if (isset($user))
                <div class="form-text">
                    Leave blank if you do not want to change the password.
                </div>
            @else
                <div class="form-text">
                    Minimum 8 characters.
                </div>
            @endif
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-6 mb-2">
            <label class="form-label">
                Confirm Password
                @if (!isset($user))
                    <span class="text-danger">*</span>
                @endif
            </label>
            <input type="password" name="password_confirmation" class="form-control rounded-0"
                placeholder="Confirm password">
        </div>
        <div class="col-md-6 mb-2">
            <label class="form-label">
                Role
                <span class="text-danger">*</span>
            </label>
            <select name="role" class="form-select @error('role') is-invalid @enderror rounded-0">
                @php
                    $roles = [
                        'admin' => 'Admin',
                        'participant' => 'Participant',
                        'reviewer' => 'Reviewer',
                    ];
                @endphp
                @foreach ($roles as $value => $label)
                    <option value="{{ $value }}" @selected(old('role', $user->role ?? 'participant') === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('role')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-6 mb-2">
            <label class="form-label">
                Status
                <span class="text-danger">*</span>
            </label>
            <select name="status" class="form-select @error('status') is-invalid @enderror rounded-0">
                <option value="active" @selected(old('status', $user->status ?? 'active') === 'active')>
                    Active
                </option>
                <option value="inactive" @selected(old('status', $user->status ?? 'active') === 'inactive')>
                    Inactive
                </option>
            </select>
            @error('status')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</div>
