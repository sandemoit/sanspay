<h5 class="mb-0 mt-4">{{ __('Update Password') }}</h5>
<p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
    {{ __('Ensure your account is using a long, random password to stay secure.') }}
</p>
<hr>
<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')
    <div class="row g-3">
        <div class="col-12">
            <label class="form-label">{{ __('Current Password') }}</label>
            <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                name="current_password" id="current_password" required autofocus autocomplete="current-password">
            @error('current_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12">
            <label class="form-label">{{ __('New Password') }}</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                id="password" required autofocus autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12">
            <label class="form-label">{{ __('Confirm Password') }}</label>
            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                name="password_confirmation" id="password_confirmation" required autofocus autocomplete="new-password">
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="d-flex flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            @if (session('status') === 'password-updated')
                <div class="d-flex align-items-center">
                    <div class="fs-3 text-success"><ion-icon name="checkmark-circle-sharp"></ion-icon>
                    </div>
                    <div class="ms-3">
                        <div class="text-success">{{ __('Password Updated.') }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</form>
