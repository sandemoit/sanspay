<x-guest-layout>
    <section class="gj ir hj sp jr i pg ">
        <!-- Section Title Start -->
        <div x-data="{ sectionTitle: `Daftar Harga` }">
            <div class=" bb ze rj ki xn vq">
                <h2 x-text="sectionTitle" class="fk vj pr kk wm on/5 gq/2 bb _b">
                </h2>
            </div>

        </div>
        <!-- Section Title End -->
        <div class="bb ze ki xn 2xl:ud-px-0">
            <!-- Dropdown Kategori -->
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="category" class="form-label">Pilih Kategori</label>
                                <select wire:model="selectedCategory" class="form-select" id="category">
                                    <option value="prepaid">Prabayar</option>
                                    <option value="postpaid">Pascabayar</option>
                                </select>
                            </div>
                        </div>

                        <!-- Dropdown Produk -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="product" class="form-label">Pilih Produk</label>
                                <select wire:model="selectedBrand" class="form-select" id="product">
                                    <option value="">-- Semua Produk --</option>
                                    @foreach ($productsByBrand as $product)
                                        <option value="{{ $product->brand }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Produk -->
                    <div class="table-responsive mt-3">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Harga Mitra</th>
                                    <th>Harga Customer</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>Rp {{ number_format($product->mitra_price, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($product->cust_price, 0, ',', '.') }}</td>
                                        <td class="{{ $product->healthy == 1 ? 'text-success' : 'text-danger' }}">
                                            {{ $product->healthy == 1 ? 'Normal' : 'Gangguan' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada produk tersedia.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
