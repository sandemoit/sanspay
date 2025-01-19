<x-guest-layout :title="$title">
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
                <div class="text-center">
                    <p>{{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                    </p>
                </div>
                <x-auth-session-status class="mb-3" :status="session('status')" />
            </div>

            <form class="sb" method="POST" action="{{ route('password.email') }}">
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
                    {!! NoCaptcha::renderJs() !!}
                    {!! NoCaptcha::display() !!}
                </div>

                <button type="submit" class="vd rj ek rc rg gh lk ml il _l gi hi">
                    {{ __('Email Password Reset Link') }}
                </button>

                <p class="sj hk xj rj ob">
                    {{ __('Already registered?') }} <a class="mk"
                        href="{{ route('login') }}">{{ __('Sign In') }}</a>
                </p>
            </form>
        </div>
    </section>
</x-guest-layout>
