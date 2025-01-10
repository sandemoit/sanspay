@foreach ($sortedGroupedProducts as $label => $products)
    @if ($label !== 'Umum')
        <p class="mb-3 text-uppercase">{{ $label }}</p>
    @endif

    <div class="desktop-view">
        <div class="row g-3">
            @foreach ($products as $product)
                <div class="col-md-6">
                    <div class="card product-card {{ $product->healthy == 0 ? 'disabled-card' : '' }}"
                        onclick="prepaid('{{ route('order.confirm', $product->code) }}')">
                        @if ($product->healthy == 0)
                            <div class="status-label">Gangguan</div>
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="product-name" style="font-size: 0.8rem;">
                                    {{ $product->name }}
                                </div>
                                <div class="product-price">
                                    <span class="badge bg-primary">
                                        @if (Auth::user()->role == 'admin')
                                            Rp{{ nominal($product->price) }}
                                        @elseif (Auth::user()->role == 'mitra')
                                            Rp{{ nominal($product->mitra_price) }}
                                        @elseif (Auth::user()->role == 'customer')
                                            Rp{{ nominal($product->cust_price) }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                            @if ($product->note)
                                <div class="product-note mt-2" style="font-size: 0.7rem;">
                                    {{ $product->note }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mobile-view">
        <div class="row g-3">
            @foreach ($products as $product)
                <div class="col-6">
                    <div class="card product-card" {{ $product->healthy == 0 ? 'disabled-card' : '' }}
                        onclick="prepaid('{{ route('order.confirm', $product->code) }}')">
                        @if ($product->healthy == 0)
                            <div class="status-label">Gangguan</div>
                        @endif
                        <div class="card-body text-center">
                            <div class="product-name" style="font-size: 0.8rem;">
                                {{ $product->name }}
                            </div>
                            <div class="product-price mt-2">
                                <h4 class="text-primary">
                                    @if (Auth::user()->role == 'admin')
                                        Rp{{ nominal($product->price) }}
                                    @elseif (Auth::user()->role == 'mitra')
                                        Rp{{ nominal($product->mitra_price) }}
                                    @elseif (Auth::user()->role == 'customer')
                                        Rp{{ nominal($product->cust_price) }}
                                    @endif
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endforeach
