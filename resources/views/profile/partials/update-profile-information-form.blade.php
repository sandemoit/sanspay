<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<h5 class="mb-0 mt-4">{{ __('User Information') }}</h5>
<p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
    {{ __("Update your account's profile information and email address.") }}
</p>
<hr>
<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')
    <div class="row g-3">
        <div class="col-6">
            <label class="form-label">{{ __('Name') }}</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $user->name) }}" name="name" id="name" required autofocus
                autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-6">
            <label class="form-label">{{ __('Email') }}</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $user->email) }}" name="email" id="email" required autofocus
                autocomplete="email">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
            <div>
                <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                    {{ __('Your email address is unverified.') }}

                    <button form="send-verification"
                        class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            </div>
        @endif
        <div class="d-flex flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <div class="d-flex align-items-center">
                    <div class="fs-3 text-success"><ion-icon name="checkmark-circle-sharp"></ion-icon>
                    </div>
                    <div class="ms-3">
                        <div class="text-success">{{ __('Profile Updated.') }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</form>
