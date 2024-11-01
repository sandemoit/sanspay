<x-guest-layout>
    <div class="row g-0 m-0">
        <div class="col-xl-6 col-lg-12">
            <div class="login-cover-wrapper">
                <div class="card shadow-none">
                    <div class="card-body">
                        <div class="text-center">
                            <p>{{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                            </p>
                        </div>
                        <!-- Session Status -->
                        <x-auth-session-status class="mb-3" :status="session('status')" />

                        <form class="form-body row g-3" method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="col-12">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required autofocus
                                    autocomplete="email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-lg-12">
                                <div class="d-grid">
                                    <x-primary-button>{{ __('Email Password Reset Link') }}</x-primary-button>
                                </div>
                            </div>
                            <div class="col-12 col-lg-12 text-center">
                                <p class="mb-0">{{ __('Already registered?') }} <a
                                        href="{{ route('login') }}">{{ __('Sign In') }}</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-12">
            <div class="position-fixed top-0 h-100 d-xl-block d-none login-cover-img">
            </div>
        </div>
    </div>
</x-guest-layout>
