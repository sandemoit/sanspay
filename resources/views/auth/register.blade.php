<x-guest-layout :title="$title">
    <section class="i pg fh rm ki xn vq gj qp gr hj rp hr">
        <!-- Bg Shapes -->
        <img src="{{ asset('/') }}web/images/shape-06.svg" alt="Shape" class="h j k" />
        <img src="{{ asset('/') }}web/images/shape-03.svg" alt="Shape" class="h l m" />
        <img src="{{ asset('/') }}web/images/shape-17.svg" alt="Shape" class="h n o" />
        <img src="{{ asset('/') }}web/images/shape-18.svg" alt="Shape" class="h p q" />

        <div class=" bb af i va sg hh sm vk xm yi _n jp hi ao">
            <!-- Bg Border -->
            <span class="rc h r s zd/2 od zg gh"></span>
            <span class="rc h r q zd/2 od xg mh"></span>

            <div class="rj">
                <h2 class="ek ck kk wm xb">{{ __('Create an Account') }}</h2>
                <p>{{ 'Silahkan isi form dibawah untuk membuat akun' }}</p>
            </div>

            <form class="sb row g-2" action="{{ route('register') }}" method="POST">
                @csrf
                <div class="col-12">
                    <label for="fullname" class="form-label">{{ __('Full Name') }}</label>
                    <input type="name" class="form-control @error('fullname') is-invalid @enderror" id="fullname"
                        name="fullname" value="{{ old('fullname') }}" required autocomplete="fullname"
                        placeholder="Masukan nama lengkap">
                    @error('fullname')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="name" class="form-label">{{ __('Username') }}</label>
                    <input type="name" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" value="{{ old('name') }}" required autocomplete="name"
                        placeholder="Masukan nama toko">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="number" class="form-label">{{ __('No HP/WhatsApp') }}</label>
                    <input type="text" class="form-control @error('number') is-invalid @enderror" id="number"
                        name="number" value="{{ old('number') }}" required autocomplete="number"
                        placeholder="Contoh: 08123456789">
                    @error('number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" value="{{ old('email') }}" required autocomplete="email"
                        placeholder="Masukan alamat email">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="pin" class="form-label">{{ __('PIN Transaksi') }}</label>
                    <div class="input-group">
                        <input type="password" class="form-control @error('pin') is-invalid @enderror" id="pin"
                            name="pin" value="{{ old('pin') }}" required autocomplete="pin"
                            placeholder="Masukan 6 Angka Unik" maxlength="6" pattern="[0-9]*" inputmode="numeric">
                        <button class="btn btn-outline-secondary" type="button" id="see-pin">Lihat</button>
                        @error('pin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-12">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <div class="input-group">
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" name="password" required autocomplete="new-password"
                            placeholder="Minimal 8 karakter">
                        <button class="btn btn-outline-secondary" type="button" id="see-password">Lihat</button>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-12">
                    <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                    <div class="input-group">
                        <input type="password"
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                            id="password_confirmation" name="password_confirmation" required
                            autocomplete="new-password" placeholder="Ulangi password">
                        <button class="btn btn-outline-secondary" type="button"
                            id="see-password-confirmation">Lihat</button>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-12">
                    <label for="kode_referral" class="form-label">{{ __('Kode Referral (Isi jika ada)') }}</label>
                    <input type="text" class="form-control @error('kode_referral') is-invalid @enderror"
                        id="kode_referral" name="kode_referral" autocomplete="new-password"
                        placeholder="Masukan kode referral (opsional)">
                    @error('kode_referral')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12 col-lg-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="flexCheckChecked"
                            name="terms" required>
                        <label class="form-check-label" for="flexCheckChecked">
                            Saya setuju dengan <a class="mk" href="#">Syarat & Ketentuan</a> layanan.
                        </label>
                    </div>
                </div>
                <div class="col-12 col-lg-12">
                    {!! NoCaptcha::renderJs() !!}
                    {!! NoCaptcha::display() !!}
                </div>

                <button type="submit" class="vd rj ek rc rg gh lk ml il _l gi hi">
                    {{ __('Sign up') }}
                </button>

                <p class="sj hk xj rj ob">
                    {{ __('Already registered?') }}
                    <a class="mk" href="{{ route('login') }}"> {{ __('Sign In') }} </a>
                </p>
            </form>
        </div>
    </section>
    @push('custom-js')
        <script src="{{ asset('web/pin-visibility.js') }}"></script>
    @endpush
</x-guest-layout>
