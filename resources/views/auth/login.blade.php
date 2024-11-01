<x-guest-layout>
    <div class="row g-0 m-0">
        <div class="col-xl-6 col-lg-12">
            <div class="login-cover-wrapper">
                <div class="card shadow-none">
                    <div class="card-body">
                        <div class="text-center">
                            <h4>{{ __('Sign In') }}</h4>
                            <p>{{ __('Sign In to your account') }}</p>
                        </div>
                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form class="form-body row g-3" method="POST" action="{{ route('login') }}">
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
                            <div class="col-12">
                                <label for="password"
                                    class="form-label @error('email') is-invalid @enderror">{{ __('Password') }}</label>
                                <input type="password" class="form-control" id="password" name="password" required
                                    autocomplete="current-password">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="remember_me"
                                        name="remember">
                                    <label class="form-check-label" for="remember_me">{{ __('Remember Me') }}</label>
                                </div>
                            </div>
                            @if (Route::has('password.request'))
                                <div class="col-12 col-lg-6 text-end">
                                    <a href="{{ route('password.request') }}">{{ __('Forgot Password?') }}</a>
                                </div>
                            @endif
                            <div class="col-12 col-lg-12">
                                <div class="d-grid">
                                    <x-primary-button>{{ __('Sign In') }}</x-primary-button>
                                </div>
                            </div>
                            <div class="col-12 col-lg-12 text-center">
                                <p class="mb-0">{{ __('Don`t have an account?') }} <a
                                        href="{{ route('register') }}">{{ __('Sign up') }}</a></p>
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
