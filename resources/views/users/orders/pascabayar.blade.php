<x-app-layout>
    @push('custom-css')
        <style>
            .product-card {
                cursor: pointer;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .product-card .status-label {
                position: absolute;
                bottom: 0;
                right: 0;
                background-color: red;
                color: white;
                font-size: 0.7rem;
                padding: 0.3rem 0.5rem;
                border-radius: 8px 0 5px 0;
                font-weight: bold;
            }

            .disabled-card {
                cursor: not-allowed;
                opacity: 0.5;
            }

            .product-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
        </style>
    @endpush

    {{--  --}}
    <div class="row">
        <div class="col-lg-7 col-md-12 col-sm-12 mx-auto">

            {{-- <div class="card p-2">
                <div class="card-body"> --}}
            <h6 class="text-uppercase text-center">{{ $title }}</h6>
            <hr>
            <form method="POST" class="row g-3" id="orderForm">
                @csrf
                <div class="col-12">
                    <label class="form-label">{{ __('Kategori') }}</label>
                    <select class="form-select" id="categoryBrand" name="categoryBrand">
                        <option disabled selected>Pilih Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->code }}" {{ !$category->healthy ? 'disabled' : '' }}>
                                {{ !$category->healthy ? "$category->name - Gangguan" : $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">{{ __('Nomor Pelanggan') }}</label>
                    <input type="text" name="target" id="target" class="form-control"
                        placeholder="Masukan Nomor Pelanggan Anda">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-12 e-money-form" style="display: none;">
                    <label class="form-label">{{ __('Nominal') }}</label>
                    <input type="text" name="amount" id="amount" class="form-control"
                        placeholder="Masukan Nominal Pembelian">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-12">
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">{{ __('BAYAR TAGIHAN') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- </div>
    </div> --}}

    <!-- Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><ion-icon name="cart-outline"></ion-icon> Konfirmasi Pesanan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="modalContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                    <button type="button" id="confirmButton" class="btn btn-primary">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>

    @push('custom-js')
        <script>
            $(document).ready(function() {
                $('#orderForm').on('submit', function(e) {
                    e.preventDefault(); // Mencegah form reload  
                    let category = $('#categoryBrand').val();
                    let customerNo = $('#target').val();

                    if (!category || !customerNo) {
                        alert('Kategori dan Nomor Pelanggan wajib diisi.');
                        return;
                    }

                    // Tampilkan modal loading terlebih dahulu  
                    const modalContent = document.getElementById('modalContent');
                    modalContent.innerHTML = `  
                        <div class="d-flex justify-content-center align-items-center" style="height: 200px;">  
                            <div class="spinner-border text-primary" role="status">  
                                <span class="visually-hidden">Loading...</span>  
                            </div>  
                        </div>  
                    `;

                    // Tampilkan modal loading  
                    const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
                    modal.show();

                    $.ajax({
                        url: "{{ route('check-bill') }}",
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                        },
                        data: {
                            code: category,
                            customer_no: customerNo,
                        },
                        success: function(response) {
                            if (response.success) {
                                // Update modal konfirmasi dengan HTML dari respons  
                                $('#modalContent').html(response.htmlContent);
                            } else {
                                toastr.error(response.message || 'Gagal mendapatkan data produk.', {
                                    timeOut: 1000
                                });
                                modal.hide();
                            }
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr);
                            toastr.error(xhr.responseText, {
                                timeOut: 1000
                            });
                            modal.hide();
                        }
                    });
                });
            });
        </script>

        {{-- transaksi --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const confirmButton = document.getElementById('confirmButton');

                confirmButton.addEventListener('click', function() {
                    const pin = document.getElementById('transaction-pin').value;
                    const target = $('#target').val();
                    const code = $('#categoryBrand').val();;

                    if (!pin) {
                        alert('PIN tidak boleh kosong!');
                        return;
                    }

                    // Tombol loading
                    confirmButton.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
                    confirmButton.disabled = true;

                    // Kirim request ke server
                    fetch('{{ route('pascaTrx') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                            },
                            body: JSON.stringify({
                                pin: pin,
                                target: target,
                                code: code,
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            confirmButton.innerHTML = 'Konfirmasi';
                            confirmButton.disabled = false;

                            if (data.success) {
                                $('#target').trigger('reset');
                                $('#categoryBrand').trigger('reset');

                                const modal = bootstrap.Modal.getInstance(document.getElementById(
                                    'confirmModal'));
                                modal.hide();

                                toastr.success(data.message, {
                                    timeOut: 1500
                                });

                                setTimeout(function() {
                                    window.location.href =
                                        "{{ route('laporan.prabayar.detail', ['id' => ':id_order']) }}"
                                        .replace(':id_order', data.id_order);
                                }, 1500);
                            } else {
                                toastr.error(data.message || 'Gagal memproses transaksi.', {
                                    timeOut: 1000
                                });
                            }
                        })
                        .catch(error => {
                            console.log('Error:', error);
                            confirmButton.innerHTML = 'Konfirmasi';
                            confirmButton.disabled = false;
                            toastr.error(error, {
                                timeOut: 1000
                            });
                        });
                });
            });
        </script>
    @endpush
</x-app-layout>
