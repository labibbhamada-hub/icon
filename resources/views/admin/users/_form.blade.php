<div class="card-body">

    <div class="row g-3">

        {{-- Name --}}
        <div class="col-md-6">

            <label for="name" class="form-label">
                Name
                <span class="text-danger">*</span>
            </label>

            <input type="text" name="name" id="name" value="{{ old('name', $user->name ?? '') }}"
                class="form-control @error('name') is-invalid @enderror rounded-0" placeholder="Full name"
                autocomplete="name">

            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>


        {{-- Email --}}
        <div class="col-md-6">

            <label for="email" class="form-label">
                Email
                <span class="text-danger">*</span>
            </label>

            <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}"
                class="form-control @error('email') is-invalid @enderror rounded-0" placeholder="user@example.com"
                autocomplete="email">

            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>


        {{-- Password --}}
        <div class="col-md-6">

            <label for="password" class="form-label">
                Password

                @if (!isset($user))
                    <span class="text-danger">*</span>
                @endif

            </label>

            <input type="password" name="password" id="password"
                class="form-control @error('password') is-invalid @enderror rounded-0"
                placeholder="{{ isset($user) ? 'Leave blank to keep current password' : 'Minimum 8 characters' }}"
                autocomplete="{{ isset($user) ? 'new-password' : 'new-password' }}">

            @if (isset($user))
                <div class="form-text">
                    Leave blank if you do not want to change the current password.
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


        {{-- Confirm Password --}}
        <div class="col-md-6">

            <label for="password_confirmation" class="form-label">
                Confirm Password

                @if (!isset($user))
                    <span class="text-danger">*</span>
                @endif

            </label>

            <input type="password" name="password_confirmation" id="password_confirmation"
                class="form-control rounded-0" placeholder="Confirm password" autocomplete="new-password">

        </div>


        {{-- Role --}}
        <div class="col-md-6">

            <label for="role" class="form-label">
                Role
                <span class="text-danger">*</span>
            </label>

            @php

                $roles = [
                    'admin' => 'Admin',
                    'participant' => 'Participant',
                    'reviewer' => 'Reviewer',
                ];

            @endphp

            <select name="role" id="role" class="form-select @error('role') is-invalid @enderror rounded-0">

                @foreach ($roles as $value => $label)
                    <option value="{{ $value }}" @selected(old('role', $user->role ?? 'participant') === $value)>
                        {{ $label }}
                    </option>
                @endforeach

            </select>

            <div class="form-text">
                Determines the user's access level.
            </div>

            @error('role')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>


        {{-- Status --}}
        <div class="col-md-6">

            <label for="status" class="form-label">
                Status
                <span class="text-danger">*</span>
            </label>

            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror rounded-0">

                <option value="active" @selected(old('status', $user->status ?? 'active') === 'active')>
                    Active
                </option>

                <option value="inactive" @selected(old('status', $user->status ?? 'active') === 'inactive')>
                    Inactive
                </option>

            </select>

            <div class="form-text">
                Inactive users cannot access restricted areas.
            </div>

            @error('status')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

    </div>

</div>
