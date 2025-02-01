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

            .sc-axis {
                background-image: url('../storage/images/cards/axis.png');
                background-color: white;
                background-repeat: no-repeat;
                background-size: auto 26px;
                background-position: 98% 50%;
            }

            .sc-by.u {
                background-image: url('../storage/images/cards/byu.svg');
                background-color: white;
                background-repeat: no-repeat;
                background-size: auto 26px;
                background-position: 98% 50%;
            }

            .sc-indosat {
                background-image: url('../storage/images/cards/indosat.png');
                background-color: white;
                background-repeat: no-repeat;
                background-size: auto 26px;
                background-position: 98% 50%;
            }

            .sc-smartfren {
                background-image: url('../storage/images/cards/smartfren.png');
                background-color: white;
                background-repeat: no-repeat;
                background-size: auto 26px;
                background-position: 98% 50%;
            }

            .sc-telkomsel {
                background-image: url('../storage/images/cards/telkomsel.png');
                background-color: white;
                background-repeat: no-repeat;
                background-size: auto 26px;
                background-position: 98% 50%;
            }

            .sc-tri {
                background-image: url('../storage/images/cards/three.png');
                background-color: white;
                background-repeat: no-repeat;
                background-size: auto 26px;
                background-position: 98% 50%;
            }

            .sc-xl {
                background-image: url('../storage/images/cards/xl-axiata.png');
                background-color: white;
                background-repeat: no-repeat;
                background-size: auto 26px;
                background-position: 98% 50%;
            }
        </style>
    @endpush


    <div class="row">
        <div class="col-xl-8 mx-auto">

            {{-- <div class="card p-2">
                <div class="card-body"> --}}
            <h6 class="text-uppercase text-center">{{ $title }}</h6>
            <hr>
            <form method="POST" class="row g-3" id="orderForm">
                @csrf
                <div class="col-12">
                    <label class="form-label">{{ __('Nomor Tujuan') }}</label>
                    <input type="text" name="phone" id="phone" class="form-control"
                        placeholder="Masukan nomor tujuan">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-12">
                    <div id="productList">
                        <div class="alert alert-danger" role="alert">
                            Silahkan Masukkan Nomor Tujuan!
                        </div>
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
                    {{-- {!! NoCaptcha::displaySubmit('modalContent', 'Konfirmasi', [
                        'class' => 'btn btn-primary',
                        'id' => 'confirmButton',
                    ]) !!} --}}
                    <button type="button" id="confirmButton" class="btn btn-primary">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>

    @push('custom-js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const phoneInput = document.getElementById('phone');
                const productList = document.getElementById('productList');
                let typingTimer;
                const doneTypingInterval = 1000;

                phoneInput.addEventListener('input', function() {
                    const phone = this.value;

                    clearTimeout(typingTimer);

                    if (phone.length >= 4) {
                        productList.innerHTML = '<div class="alert alert-info">Mencari produk...</div>';
                        typingTimer = setTimeout(() => checkNumber(phone), doneTypingInterval);
                    } else {
                        productList.innerHTML =
                            '<div class="alert alert-danger">Silahkan Masukkan Nomor Tujuan!</div>';
                    }
                });

                function checkNumber(phone) {
                    // Debug: Log request attempt

                    const formData = new FormData();
                    formData.append('type', '{{ $type }}');
                    formData.append('phone', phone);

                    // Use jQuery AJAX if available
                    if (typeof $ !== 'undefined') {
                        $.ajax({
                            url: '{{ secure_url(route('order.checkProvider')) }}',
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                            },
                            success: function(response) {
                                handleResponse(response);
                            },
                            error: function(xhr, status, error) {
                                console.error('Error:', error);
                                productList.innerHTML = `
                        <div class="alert alert-danger">
                            Terjadi kesalahan: ${error}
                        </div>
                    `;
                            }
                        });
                    }
                    // Fallback to fetch if jQuery is not available
                    else {
                        fetch('{{ route('order.checkProvider') }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content'),
                                },
                                credentials: 'same-origin'
                            })
                            .then(response => response.json())
                            .then(data => handleResponse(data))
                            .catch(error => {
                                console.error('Error:', error);
                                productList.innerHTML = `
                    <div class="alert alert-danger">
                        Terjadi kesalahan: ${error.message}
                    </div>
                `;
                            });
                    }
                }

                function handleResponse(data) {
                    // Remove existing provider classes
                    const providerClasses = [
                        'sc-telkomsel',
                        'sc-indosat',
                        'sc-xl',
                        'sc-axis',
                        'sc-tri',
                        'sc-smartfren',
                        'sc-byu'
                    ];
                    phoneInput.classList.remove(...providerClasses);

                    // Update UI
                    productList.innerHTML = data.service;
                    if (data.class) {
                        phoneInput.classList.add(data.class);
                    }
                }
            });
        </script>

        <script>
            function prepaid(url) {
                // Tampilkan modal loading terlebih dahulu
                const modalContent = document.getElementById('modalContent');
                modalContent.innerHTML = `
                    <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `;

                const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
                modal.show();

                fetch(url, {
                        method: 'GET', // Menggunakan GET karena mengambil data
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const targetPhone = document.getElementById('phone').value;

                            // Render data ke modal atau elemen lain
                            modalContent.innerHTML = `
                                <div class="form-group mb-3">
                                    <label for="order-target">Tujuan</label>
                                    <input type="text" data-code="${data.product.code}" class="form-control" id="order-target" name="order-target" value="${targetPhone}" readonly>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="product-name">Nama Produk</label>
                                    <input type="text" class="form-control" id="product-name" value="${data.product.name}" readonly>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="product-info">Informasi Produk</label>
                                    <textarea class="form-control" id="product-info" rows="2" readonly>${data.product.note}</textarea>
                                </div>
                                <div class="row mb-3">
                                    <div class="form-group col-md-6 mb-3">
                                        <label for="price">Harga</label>
                                        <input type="text" class="form-control" id="price" value="Rp ${data.price}" readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="balance">Sisa Saldo</label>
                                        <input type="text" class="form-control" id="balance" value="Rp ${data.userSaldo}" readonly>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="transaction-pin">PIN Transaksi Anda <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="transaction-pin" name="transaction-pin" required>
                                    <input type="hidden" id="apaajalah" value="${data.token}">
                                </div>
                            `;
                        } else {
                            toastr.error(data.message || 'Gagal mendapatkan data produk.', {
                                timeOut: 1000
                            });
                            modal.hide();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    });
            }
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const confirmButton = document.getElementById('confirmButton');

                confirmButton.addEventListener('click', function() {
                    const pinInput = document.getElementById('transaction-pin');
                    const apaajalah = document.getElementById('apaajalah').value;
                    const targetPhone = document.getElementById('phone').value;
                    const orderTarget = document.getElementById('order-target');
                    const code = orderTarget.getAttribute('data-code');
                    const pin = pinInput.value;

                    if (!pin) {
                        alert('PIN tidak boleh kosong!');
                        return;
                    }

                    // Tombol loading
                    confirmButton.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
                    confirmButton.disabled = true;

                    // Kirim request ke server
                    fetch('{{ route('order.proses') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                            },
                            body: JSON.stringify({
                                pin: pin,
                                target: targetPhone,
                                code: code,
                                token: apaajalah
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            confirmButton.innerHTML = 'Konfirmasi';
                            confirmButton.disabled = false;

                            if (data.success) {
                                $('#phone').trigger('reset');
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
