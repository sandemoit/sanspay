<h5 class="mb-0 mt-4">{{ __('Update PIN Transaction') }}</h5>
<p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
    {{ __('Make sure your transactions use a hard-to-guess PIN to stay safe.') }}
</p>
<hr>
<form method="post" action="{{ route('pin.update') }}">
    @csrf
    @method('put')
    <div class="row g-3">
        <div class="col-12">
            <label class="form-label">{{ __('PIN Security New') }}</label>
            <input type="password" class="form-control @error('pin') is-invalid @enderror" name="pin" id="pin"
                required autofocus autocomplete="new-password">
            @error('pin')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12">
            <label class="form-label">{{ __('Confirm PIN Security') }}</label>
            <input type="password" class="form-control @error('pin_confirmation') is-invalid @enderror"
                name="pin_confirmation" id="pin_confirmation" required autocomplete="new-password">
            @error('pin_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12">
            <label class="form-label">{{ __('Current Password') }}</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                id="password" required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="d-flex flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            @if (session('success'))
                <div class="d-flex align-items-center">
                    <div class="fs-3 text-success"><ion-icon name="checkmark-circle-sharp"></ion-icon></div>
                    <div class="ms-3">
                        <div class="text-success">{{ session('success') }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</form>
