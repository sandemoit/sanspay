<!--start sidebar -->
<aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('storage') }}/images/logo.svg" class="logo-icon" alt="logo" width="60" height="60">
        </div>
        <div>
            <h4 class="logo-text">{{ config('app.name') }}</h4>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">

        @php
            $segment1 = Request::segment(1);
        @endphp

        @if (Auth::user()->role == 'admin' && $segment1 == 'admin')
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <div class="parent-icon">
                        <ion-icon name="home-outline"></ion-icon>
                    </div>
                    <div class="menu-title">{{ __('Dashboard') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('order') }}">
                    <div class="parent-icon">
                        <ion-icon name="analytics-outline"></ion-icon>
                    </div>
                    <div class="menu-title">{{ __('Transactions') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('provider') }}">
                    <div class="parent-icon">
                        <ion-icon name="globe-outline"></ion-icon>
                    </div>
                    <div class="menu-title">{{ __('Provider') }}</div>
                </a>
            </li>
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon">
                        <ion-icon name="wallet-outline"></ion-icon>
                    </div>
                    <div class="menu-title">{{ __('Deposit') }}</div>
                </a>
                <ul>
                    <li> <a href="{{ route('admin.deposit') }}">
                            <ion-icon name="ellipse-outline"></ion-icon>{{ __('Manage Deposit') }}
                        </a>
                    </li>
                    <li> <a href="{{ route('admin.deposit.metode') }}">
                            <ion-icon name="ellipse-outline"></ion-icon>{{ __('Method Deposit') }}
                        </a>
                    </li>
                    <li> <a href="widgets-data-widgets.html">
                            <ion-icon name="ellipse-outline"></ion-icon>{{ __('Payment Deposit') }}
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon">
                        <ion-icon name="storefront-outline"></ion-icon>
                    </div>
                    <div class="menu-title">{{ __('Credit & PPOB') }}</div>
                </a>
                <ul>
                    <li> <a href="{{ route('pulsa-ppob.product') }}">
                            <ion-icon name="ellipse-outline"></ion-icon>{{ __('Product') }}
                        </a>
                    </li>
                    <li> <a href="{{ route('pulsa-ppob.category') }}">
                            <ion-icon name="ellipse-outline"></ion-icon>{{ __('Category') }}
                        </a>
                    </li>
                    <li> <a href="{{ route('pulsa-ppob.profit') }}">
                            <ion-icon name="ellipse-outline"></ion-icon>{{ __('Profit') }}
                        </a>
                    </li>
                    <li> <a href="{{ route('pulsa-ppob.point') }}">
                            <ion-icon name="ellipse-outline"></ion-icon>{{ __('Point') }}
                        </a>
                    </li>
                    <li> <a href="#">
                            <ion-icon name="ellipse-outline"></ion-icon>{{ __('Report') }}
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('admin.ticket') }}">
                    <div class="parent-icon">
                        <ion-icon name="chatbubbles-outline"></ion-icon>
                    </div>
                    <div class="menu-title">{{ __('Ticket Helpdesk') }}</div>
                </a>
            </li>
        @elseif (Auth::user()->role == 'customer' || Auth::user()->role == 'admin' || $segment1 != 'admin')
            <li>
                <a href="{{ route('dashboard') }}">
                    <div class="parent-icon">
                        <ion-icon name="home-outline"></ion-icon>
                    </div>
                    <div class="menu-title">{{ __('Dashboard') }}</div>
                </a>
            </li>
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon">
                        <ion-icon name="wallet-outline"></ion-icon>
                    </div>
                    <div class="menu-title">{{ __('Deposit') }}</div>
                </a>
                <ul>
                    <li> <a href="{{ route('deposit.new') }}">
                            <ion-icon name="ellipse-outline"></ion-icon>{{ __('Request Deposit') }}
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        @if (Auth::user()->role == 'admin' && $segment1 != 'admin')
            <li class="menu-label">{{ __('Admin Area') }}</li>

            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <div class="parent-icon">
                        <ion-icon name="desktop-outline"></ion-icon>
                    </div>
                    <div class="menu-title">{{ __('Dashboard Admin') }}</div>
                </a>
            </li>
        @elseif(Auth::user()->role == 'admin' && $segment1 == 'admin')
            <li class="menu-label">{{ __('User Area') }}</li>
            <li>
                <a href="{{ route('dashboard') }}">
                    <div class="parent-icon">
                        <ion-icon name="globe-outline"></ion-icon>
                    </div>
                    <div class="menu-title">{{ __('Lihat Aplikasi') }}</div>
                </a>
            </li>
        @endif
    </ul>
    <!--end navigation-->
</aside>
<!--end sidebar -->
