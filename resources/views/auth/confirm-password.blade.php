<x-guest-layout :title="$title">
    <div class="row g-0 m-0">
        <div class="col-xl-6 col-lg-12">
            <div class="login-cover-wrapper">
                <div class="card shadow-none">
                    <div class="card-body">
                        <div class="text-center">
                            <p>{{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                            </p>
                        </div>

                        <form class="form-body row g-3" method="POST" action="{{ route('password.confirm') }}">
                            @csrf

                            <!-- Password Reset Token -->
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <div class="col-12">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required autofocus autocomplete="current-password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-lg-12">
                                <div class="d-grid">
                                    <x-primary-button>{{ __('Confirm') }}</x-primary-button>
                                </div>
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
