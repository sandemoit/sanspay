<!-- Nav Pills -->
<div class="nav-pills-container">
    <ul class="nav nav-pills" role="tablist">
        @foreach ($sortedGroupedProducts as $label => $products)
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="tab-{{ Str::slug($label) }}"
                    data-bs-toggle="pill" href="#panel-{{ Str::slug($label) }}" role="tab"
                    aria-controls="panel-{{ Str::slug($label) }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    {{ $label }}
                </a>
            </li>
        @endforeach
    </ul>
</div>

<!-- Tab Content -->
<div class="tab-content mt-4">
    @foreach ($sortedGroupedProducts as $label => $products)
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="panel-{{ Str::slug($label) }}"
            role="tabpanel" aria-labelledby="tab-{{ Str::slug($label) }}">
            <!-- Desktop View -->
            <div class="desktop-view">
                <div class="row g-3">
                    @foreach ($products as $product)
                        <div class="col-md-6">
                            <div class="card sanspay product-card {{ $product->healthy == 0 ? 'disabled-card' : '' }}"
                                onclick="prepaid('{{ route('order.confirm', $product->code) }}')">
                                @if ($product->discount == 1)
                                    <div class="discount-label">Promo Terbatas!</div>
                                @endif
                                @if ($product->healthy == 0)
                                    <div class="status-label">Gangguan</div>
                                @endif
                                <div class="card-body card-sanspay">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="product-name" style="font-size: 0.8rem;">
                                            {{ $product->name }}
                                        </div>
                                        <div class="product-price">
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
                                    @if ($product->note != '-')
                                        <div class="product-note mt-2" style="font-size: 0.8rem;">
                                            {{ $product->note }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Mobile View -->
            <div class="mobile-view">
                <div class="row g-3">
                    @foreach ($products as $product)
                        <div class="col-12 mb-0 mt-0">
                            <div class="card sanspay product-card {{ $product->healthy == 0 ? 'disabled-card' : '' }}"
                                onclick="prepaid('{{ route('order.confirm', $product->code) }}')">
                                @if ($product->discount == 1)
                                    <div class="discount-label">Promo Terbatas!</div>
                                @endif
                                @if ($product->healthy == 0)
                                    <div class="status-label">Gangguan</div>
                                @endif
                                <div class="card-body card-sanspay">
                                    <div class="product-name">
                                        {{ $product->name }}
                                    </div>
                                    @if ($product->note != '-')
                                        <div class="product-desc">
                                            {{ $product->note }}
                                        </div>
                                    @endif
                                    <div class="product-price mt-2">
                                        <h4 class="text-primary font-semibold" style="font-size: 1.2rem;">
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
        </div>
    @endforeach
</div>
