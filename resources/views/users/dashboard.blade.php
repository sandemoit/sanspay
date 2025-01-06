@push('custom-css')
    <style>
        .carousel-item {
            max-height: auto;
            object-fit: cover;
        }

        @media (max-width: 768px) {
            .carousel-item {
                max-height: auto;
            }
        }
    </style>
@endpush
<x-app-layout>
    @include('layouts.breadcrumbs')
    <div class="row row-cols-1 row-cols-lg-2 row-cols-xxl-2">
        <div class="col mb-3">
            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach (announcement()->where('type', 'banner') as $key => $announcement)
                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                            <a href="{{ route('announcement', $announcement->slug) }}">
                                <img src="{{ asset($announcement->image) }}" class="d-block w-100" alt="...">
                            </a>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class=" mt-1">
                                    <h5 class="stats-number"><ion-icon name="wallet-outline"></ion-icon> Rp
                                        {{ nominal(Auth::user()->saldo) }}
                                    </h5>
                                </div>
                                <div>
                                    <a href="{{ route('deposit.new') }}"
                                        class="btn btn-primary btn-sm text-white"><ion-icon
                                            name="wallet-outline"></ion-icon> Deposit</a>
                                    {{-- <a href="{{ route('deposit.new') }}"
                                        class="btn btn-primary btn-sm text-white"><ion-icon
                                            name="repeat-outline"></ion-icon> Kirim</a>
                                    <a href="{{ route('deposit.new') }}"
                                        class="btn btn-primary btn-sm text-white"><ion-icon
                                            name="download-outline"></ion-icon> Tarik</a>
                                    <a href="{{ route('deposit.new') }}"
                                        class="btn btn-primary btn-sm text-white"><ion-icon
                                            name="cash-outline"></ion-icon> Mutasi</a> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (Auth::user()->saldo <= '5000')
                <div class="alert alert-primary" role="alert">
                    Pelanggan yang terhormat, saldo Anda sudah <b>menipis</b>, segera
                    lakukan deposit ya? kami tunggu nih :)
                </div>
            @endif

            <div class="card mt-3">
                <div class="card-body">
                    <!-- Tabs navs -->
                    <ul class="nav nav-tabs mb-3 nav-tabs-custom nav-justified" id="ex-with-icons" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="ex-with-icons-tab-1" href="#tab-prepaid" role="tab"
                                aria-controls="tab-prepaid" aria-selected="true" data-bs-toggle="tab"><ion-icon
                                    name="cart-outline"></ion-icon> Prabayar</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="ex-with-icons-tab-2" href="#tab-postpaid" role="tab"
                                aria-controls="tab-postpaid" aria-selected="false" data-bs-toggle="tab"><ion-icon
                                    name="cart-outline"></ion-icon> Pascabayar</a>
                        </li>
                    </ul>
                    <!-- Tabs navs -->

                    <!-- Tabs content -->
                    <div class="tab-content text-muted" id="ex-with-icons-content">
                        <div class="tab-pane fade show active" id="tab-prepaid" role="tabpanel"
                            aria-labelledby="ex-with-icons-tab-1">
                            <div class="row mt-3 text-center">
                                <div class="col-4 mb-3">
                                    <a href="{{ route('order.paket-telepon') }}">
                                        <div class="avatar avatar-40 no-shadow border-0 mb-1">
                                            <ion-icon name="call-outline"
                                                style="font-size: 30px;color: #00d1ff;"></ion-icon>
                                        </div>
                                        <p title="Paket Telepon/SMS">Paket Telepon/SMS</p>
                                    </a>
                                </div>
                                <div class="col-4 mb-3">
                                    <a href="{{ route('order.pulsa-reguler') }}">
                                        <div class="avatar avatar-40 no-shadow border-0 mb-1">
                                            <ion-icon name="phone-portrait-outline"
                                                style="font-size: 30px;color: #00d1ff;"></ion-icon>
                                        </div>
                                        <p title="Pulsa Reguler">Pulsa Reguler</p>
                                    </a>
                                </div>
                                <div class="col-4 mb-3">
                                    <a href="{{ route('order.paket-internet') }}">
                                        <div class="avatar avatar-40 no-shadow border-0 mb-1">
                                            <ion-icon name="cellular-outline"
                                                style="font-size: 30px;color: #00d1ff;"></ion-icon>
                                        </div>
                                        <p title="Paket Data Internet">Paket Data Internet</p>
                                    </a>
                                </div>
                                <div class="col-4 mb-3">
                                    <a href="{{ route('order.pulsa-transfer') }}">
                                        <div class="avatar avatar-40 no-shadow border-0 mb-1">
                                            <ion-icon name="repeat-outline"
                                                style="font-size: 30px;color: #00d1ff;"></ion-icon>
                                        </div>
                                        <p title="Pulsa Transfer">Pulsa Transfer</p>
                                    </a>
                                </div>
                                <div class="col-4 mb-3">
                                    <a href="{{ route('order.emonney') }}">
                                        <div class="avatar avatar-40 no-shadow border-0 mb-1">
                                            <ion-icon name="wallet-outline"
                                                style="font-size: 30px;color: #00d1ff;"></ion-icon>
                                        </div>
                                        <p title="E-Money">E-Money</p>
                                    </a>
                                </div>
                                <div class="col-4 mb-3">
                                    <a href="{{ route('order.pulsa-reguler') }}">
                                        <div class="avatar avatar-40 no-shadow border-0 mb-1">
                                            <ion-icon name="flash-outline"
                                                style="font-size: 30px;color: #00d1ff;"></ion-icon>
                                        </div>
                                        <p title="Token PLN">Token PLN</p>
                                    </a>
                                </div>
                                <div class="col-4 mb-3">
                                    <a href="{{ route('order.pulsa-reguler') }}">
                                        <div class="avatar avatar-40 no-shadow border-0 mb-1">
                                            <ion-icon name="game-controller-outline"
                                                style="font-size: 30px;color: #00d1ff;"></ion-icon>
                                        </div>
                                        <p title="Top Up Game">Top Up Game</p>
                                    </a>
                                </div>
                                <div class="col-4 mb-3">
                                    <a href="{{ route('order.pulsa-reguler') }}">
                                        <div class="avatar avatar-40 no-shadow border-0 mb-1">
                                            <ion-icon name="tv-outline"
                                                style="font-size: 30px;color: #00d1ff;"></ion-icon>
                                        </div>
                                        <p title="TV">TV</p>
                                    </a>
                                </div>
                                <div class="col-4 mb-3">
                                    <a href="{{ route('order.pulsa-reguler') }}">
                                        <div class="avatar avatar-40 no-shadow border-0 mb-1">
                                            <ion-icon name="ticket-outline"
                                                style="font-size: 30px;color: #00d1ff;"></ion-icon>
                                        </div>
                                        <p title="Voucher">Voucher</p>
                                    </a>
                                </div>
                                <div class="col-4 mb-0">
                                    <a href="{{ route('order.pulsa-reguler') }}">
                                        <div class="avatar avatar-40 no-shadow border-0 mb-1">
                                            <ion-icon name="planet-outline"
                                                style="font-size: 30px;color: #00d1ff;"></ion-icon>
                                        </div>
                                        <p title="Lainnya">Voucher Lainnya</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-postpaid" role="tabpanel"
                            aria-labelledby="ex-with-icons-tab-2">
                            Tab 2 content
                        </div>
                    </div>
                    <!-- Tabs content -->
                </div>
            </div>
        </div>
        <div class="col mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-primary text-announcement">
                        <ion-icon name="information-circle-outline"></ion-icon>
                        @if (count(announcement()))
                            {{ count(announcement()->where('type', 'static')) }} Informasi Terbaru
                        @else
                            Belum ada informasi
                        @endif
                    </div>
                    @if (count(announcement()))
                        @foreach (announcement()->where('type', 'static') as $key)
                            <div class="alert alert-primary" role="alert">
                                <div class="text-left mb-2">
                                    <span class="badge bg-primary">INFO</span> {{ $key->created_at }}
                                </div>
                                <div>
                                    <div><strong>{{ $key->title }}</strong></div>
                                    {{ $key->description }}
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center">Tidak ada informasi terbaru</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!--end row-->
</x-app-layout>
@push('custom-js')
    <script>
        $('.nav-tabs').tab();
    </script>
@endpush
