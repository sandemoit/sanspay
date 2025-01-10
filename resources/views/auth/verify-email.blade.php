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
                <p class="text-center mb-4">
                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </p>
                @if (session('status') == 'verification-link-sent')
                    <p class="text-center mb-4 text-success">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </p>
                @endif
            </div>

            <div class="mt-4 flex text-center justify-between">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="vd rj ek rc rg gh lk ml il _l gi hi">
                        {{ __('Resend Verification Email') }}
                    </button>
                </form>

                <p class="sj hk xj rj ob">
                    <a class="mk" href="{{ route('logout') }}"> {{ __('Log Out') }} </a>
                </p>
            </div>
        </div>
    </section>
</x-guest-layout>
