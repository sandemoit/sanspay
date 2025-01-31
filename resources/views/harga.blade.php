@push('custom-css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <style>
        @media (max-width: 768px) {
            .product-name {
                white-space: nowrap;
                /* Mencegah teks terpotong */
                overflow: hidden;
                /* Menyembunyikan teks yang tidak muat */
                text-overflow: ellipsis;
                /* Menambahkan elipsis jika teks terlalu panjang */
                font-size: 0.85rem;
            }

            .table th,
            .table td {
                vertical-align: middle;
                /* Menyelaraskan teks di tengah */
            }
        }
    </style>
@endpush
<x-guest-layout :title="$title">
    <section class="gj hj sp jr i pg ">
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
                    <form method="GET" action="{{ route('harga-produk') }}">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="brand" class="form-label">Pilih Produk</label>
                                    <select name="brand" class="form-select" id="brand"
                                        onchange="this.form.submit()">
                                        <option value="">-- Semua Produk --</option>
                                        @foreach ($productsByBrand as $product)
                                            <option value="{{ $product->brand }}"
                                                {{ request('brand') == $product->brand ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Tabel Produk -->
                    <div class="table-responsive mt-3">
                        <table class="table table-striped" id="productsTable">
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
                                        <td class="product-name">{{ $product->name }}</td>
                                        <td class="product-name">Rp
                                            {{ number_format($product->mitra_price, 0, ',', '.') }}</td>
                                        <td class="product-name">Rp
                                            {{ number_format($product->cust_price, 0, ',', '.') }}</td>
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
    @push('custom-scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#productsTable').DataTable({
                    processing: true,
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
                    },
                    pageLength: 25,
                    ordering: true,
                    responsive: true,
                    // Optimasi render
                    deferRender: true,
                    scroller: true,
                    columnDefs: [{
                        targets: [1, 2],
                        render: function(data, type, row) {
                            if (type === 'sort') {
                                return data.replace(/[^\d]/g, '');
                            }
                            return data;
                        }
                    }],
                    // Optimasi loading
                    initComplete: function() {
                        $(this.api().table().container()).find('input').addClass('form-control');
                    }
                });
            });
        </script>
    @endpush
</x-guest-layout>
