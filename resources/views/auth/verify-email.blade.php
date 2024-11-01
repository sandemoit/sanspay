<x-guest-layout>
    <div class="row g-0 m-0">
        <div class="col-xl-6 col-lg-12">
            <div class="login-cover-wrapper">
                <div class="card shadow-none">
                    <div class="card-body p-4">
                        <p class="text-center mb-4">
                            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                        </p>
                        @if (session('status') == 'verification-link-sent')
                            <p class="text-center mb-4">
                                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                            </p>
                        @endif
                        <div class="mt-4 flex text-center justify-between">
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf

                                <div>
                                    <x-primary-button>
                                        {{ __('Resend Verification Email') }}
                                    </x-primary-button>
                                </div>
                            </form>

                            <a href="{{ route('logout') }}" class="btn btn-danger mt-3">
                                {{ __('Log Out') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-lg-12">
        <div class="position-fixed top-0 h-100 d-xl-block d-none login-cover-img">
        </div>
    </div>
</x-guest-layout>
