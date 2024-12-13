    <x-guest-layout>
        <div class="row g-0 m-0">
            <div class="col-xl-6 col-lg-12">
                <div class="login-cover-wrapper">
                    <div class="card shadow-none">
                        <div class="card-body p-4">
                            <div class="text-center">
                                <h4>{{ __('Register') }}</h4>
                                <p>{{ __('Sign up to your account') }}</p>
                            </div>
                            <!-- Session Status -->
                            <x-auth-session-status class="mb-4" :status="session('status')" />

                            <form class="form-body row g-3" method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="col-12">
                                    <label for="name" class="form-label">{{ __('Name') }}</label>
                                    <input type="name" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" required autofocus
                                        autocomplete="name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="email" class="form-label">{{ __('Email') }}</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}" required autofocus
                                        autocomplete="email">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="number" class="form-label">{{ __('WhatsApp') }}</label>
                                    <input type="text" class="form-control @error('number') is-invalid @enderror"
                                        id="number" name="number" value="{{ old('number') }}" required autofocus
                                        autocomplete="number">
                                    @error('number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="password" class="form-label">{{ __('Password') }}</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" value="{{ old('password') }}" required autofocus
                                        autocomplete="new-password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="password_confirmation"
                                        class="form-label">{{ __('Confirm Password') }}</label>
                                    <input type="password"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        id="password_confirmation" name="password_confirmation"
                                        value="{{ old('password_confirmation') }}" required autofocus
                                        autocomplete="new-password">
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-lg-12">
                                    <div class="d-grid">
                                        <x-primary-button>{{ __('Sign up') }}</x-primary-button>
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
                <div class="position-fixed top-0 h-100 d-xl-block d-none register-cover-img">
                </div>
            </div>
    </x-guest-layout>
