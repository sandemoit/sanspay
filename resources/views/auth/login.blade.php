<x-guest-layout>
    <section class="i pg fh rm ki xn vq gj qp gr hj rp hr">
        <!-- Bg Shapes -->
        <img src="{{ asset('/') }}web/images/shape-06.svg" alt="Shape" class="h j k" />
        <img src="{{ asset('/') }}web/images/shape-03.svg" alt="Shape" class="h l m" />
        <img src="{{ asset('/') }}web/images/shape-17.svg" alt="Shape" class="h n o" />
        <img src="{{ asset('/') }}web/images/shape-18.svg" alt="Shape" class="h p q" />

        <div class=" bb af i va sg hh sm vk xm yi _n jp hi ao kp">
            <!-- Bg Border -->
            <span class="rc h r s zd/2 od zg gh"></span>
            <span class="rc h r q zd/2 od xg mh"></span>

            <div class="rj">
                <h2 class="ek ck kk wm xb">{{ __('Sign In') }}</h2>
                <p>{{ __('Sign In to your account') }}</p>
                <x-auth-session-status class="mb-4" :status="session('status')" />
            </div>

            <form class="sb" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="wb">
                    <label class="rc kk wm vb" for="email">{{ __('Email') }}</label>
                    <input type="text" id="email" name="email" value="{{ old('email') }}" required autofocus
                        autocomplete="email" placeholder="example@gmail.com"
                        class="vd hh rg zk _g ch hm dm fm pl/50 xi mi sm xm pm dn/40 @error('email') is-invalid @enderror" />
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="wb">
                    <label class="rc kk wm vb" for="password">{{ __('Password') }}</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password"
                        placeholder="**************"
                        class="vd hh rg zk _g ch hm dm fm pl/50 xi mi sm xm pm dn/40 @error('password') is-invalid @enderror" />
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row mb-3">
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
                </div>

                <button type="submit" class="vd rj ek rc rg gh lk ml il _l gi hi">
                    Sign In
                </button>

                <p class="sj hk xj rj ob">
                    {{ __('Don`t have an account?') }}
                    <a class="mk" href="{{ route('register') }}"> {{ __('Sign up') }} </a>
                </p>
            </form>
        </div>
    </section>
</x-guest-layout>
