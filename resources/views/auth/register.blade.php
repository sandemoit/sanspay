<x-guest-layout>
    <div class="container">
        <div class="row">
            <div class="col-xl-5 col-lg-6 col-md-7 mx-auto mt-3">
                <div class="card radius-10">
                    <div class="card-body p-4">
                        <div class="text-center">
                            <h4>Sign Up</h4>
                            <p>Creat New account</p>
                        </div>
                        <form class="form-body row g-2" method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="col-12">
                                <label for="fullname" class="form-label">{{ __('Full Name') }}</label>
                                <input type="name" class="form-control @error('fullname') is-invalid @enderror"
                                    id="fullname" name="fullname" value="{{ old('fullname') }}" required autofocus
                                    autocomplete="fullname">
                                @error('fullname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="name" class="form-label">{{ __('Username') }}</label>
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
                                <label for="pin" class="form-label">{{ __('PIN Transaksi') }}</label>
                                <input type="text" class="form-control @error('pin') is-invalid @enderror"
                                    id="number" name="pin" value="{{ old('pin') }}" required autofocus
                                    autocomplete="pin">
                                @error('pin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required autofocus autocomplete="new-password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="password_confirmation"
                                    class="form-label">{{ __('Confirm Password') }}</label>
                                <input type="password"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    id="password_confirmation" name="password_confirmation" required autofocus
                                    autocomplete="new-password">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="kode_referral"
                                    class="form-label">{{ __('Kode Referral (Optional)') }}</label>
                                <input type="password" class="form-control @error('kode_referral') is-invalid @enderror"
                                    id="kode_referral" name="kode_referral" autofocus autocomplete="new-password">
                                @error('kode_referral')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-lg-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckChecked"
                                        name="terms" required>
                                    <label class="form-check-label" for="flexCheckChecked">
                                        Saya setuju dengan <a href="#">Syarat & Ketentuan</a> layanan.
                                    </label>
                                </div>
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
    </div>
</x-guest-layout>
