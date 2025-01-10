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
                <h2 class="ek ck kk wm xb">{{ __('Reset Password') }}</h2>
                <p>{{ __('Change your password account') }}</p>
                <x-auth-session-status class="mb-4" :status="session('status')" />
            </div>

            <form class="sb" method="POST" action="{{ route('password.store') }}">
                @csrf
                <div class="wb">
                    <label class="rc kk wm vb" for="email">{{ __('Email') }}</label>
                    <input type="text" id="email" name="email" value="{{ old('email', $request->email) }}"
                        required autofocus autocomplete="email" placeholder="example@gmail.com"
                        class="vd hh rg zk _g ch hm dm fm pl/50 xi mi sm xm pm dn/40 @error('email') is-invalid @enderror" />
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="wb">
                    <label class="rc kk wm vb" for="password">{{ __('Password') }}</label>
                    <input type="password" id="password" name="password" required autocomplete="password"
                        placeholder="**************"
                        class="vd hh rg zk _g ch hm dm fm pl/50 xi mi sm xm pm dn/40 @error('password') is-invalid @enderror" />
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="wb">
                    <label class="rc kk wm vb" for="password_confirmation">{{ __('Confirm Password') }}</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        autocomplete="password_confirmation" placeholder="**************"
                        class="vd hh rg zk _g ch hm dm fm pl/50 xi mi sm xm pm dn/40 @error('password_confirmation') is-invalid @enderror" />
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="vd rj ek rc rg gh lk ml il _l gi hi">
                    {{ __('Reset Password') }}
                </button>
            </form>
        </div>
    </section>
</x-guest-layout>
