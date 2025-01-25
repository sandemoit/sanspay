<x-app-layout>
    @push('custom-css')
        <link href="{{ asset('/') }}plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

        <style>
            .card-body {
                padding: 15px;
                border-radius: 5px;
            }

            .card.details-card {
                /* Tambahkan kelas khusus */
                margin: 20px auto;
                max-width: 100%;
                color: white
            }

            .card-body ul li {
                display: flex;
                justify-content: flex-start;
                font-family: Arial, sans-serif;
                font-size: 14px;
                line-height: 1.8;
            }

            .card-body ul li .label {
                width: 130px;
                /* Lebar tetap untuk label */
                font-weight: bold;
            }

            .card-body ul li .value {
                flex: 1;
                width: 250px;
                margin-left: 1rem
                    /* Isi sisa ruang */
            }

            .table-container {
                /* Untuk tabel */
                margin-top: 20px;
                overflow-x: auto;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }
        </style>
    @endpush

    <div class="row row-cols-1 row-cols-lg-2 row-cols-xxl-4">
        <div class="col mb-3">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-2">
                        <div>
                            <p class="mb-0 fs-6">{{ __('Transaction Success') }}</p>
                        </div>
                        <div class="ms-auto widget-icon-small text-white bg-gradient-success">
                            <ion-icon name="checkmark-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <div>
                            <h4 class="mb-0">{{ $statuses['success'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col mb-3">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-2">
                        <div>
                            <p class="mb-0 fs-6">{{ __('Transaction Pending') }}</p>
                        </div>
                        <div class="ms-auto widget-icon-small text-white bg-gradient-warning">
                            <ion-icon name="timer-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <div>
                            <h4 class="mb-0">{{ $statuses['pending'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col mb-3">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-2">
                        <div>
                            <p class="mb-0 fs-6">{{ __('Transaction Error') }}</p>
                        </div>
                        <div class="ms-auto widget-icon-small text-white bg-gradient-danger">
                            <ion-icon name="close-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <div>
                            <h4 class="mb-0">{{ $statuses['cancel'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col mb-3">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-2">
                        <div>
                            <p class="mb-0 fs-6">{{ __('Total Saldo Terpakai') }}</p>
                        </div>
                        <div class="ms-auto widget-icon-small text-white bg-gradient-info">
                            <ion-icon name="cash-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <div>
                            <h4 class="mb-0">{{ 'Rp ' . nominal($statuses['total_price']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="history-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>{{ __('Order ID') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('Data') }}</th>
                            <th>{{ __('Keterangan/SN') }}</th>
                            <th>{{ __('Note') }}</th>
                            <th>{{ __('Refund') }}</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        {{-- here to fetch data --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailTrxModal" tabindex="-1" aria-labelledby="detailTrxLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailTrxLabel">{{ __('Detail Transaksi') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="card bg-success details-card">
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li><span class="label">Trxid</span> : <span class="value" id="order_id">-</span></li>
                            <li><span class="label">Pengirim</span> : <span class="value" id="pengirim">-</span></li>
                            <li><span class="label">Kode</span> : <span class="value" id="code">-</span></li>
                            <li><span class="label">Detail Produk</span> : <span class="value" id="name">-</span>
                            </li>
                            <li><span class="label">Nomor Tujuan</span> : <span class="value" id="data">-</span>
                            </li>
                            <li><span class="label">Status</span> : <span class="value" id="status">-</span></li>
                            <li><span class="label">Note</span> : <span class="value" id="note">-</span></li>
                            <li><span class="label">ket/SN</span> : <span class="value" id="sn">-</span></li>
                            <li><span class="label">Harga</span> : <span class="value" id="price">-</span></li>
                            <li><span class="label">Transaksi</span> : <span class="value" id="created_at">-</span>
                            </li>
                            <li><span class="label">Update</span> : <span class="value" id="updated_at">-</span></li>
                        </ul>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    @push('custom-js')
        <script src="{{ asset('/') }}plugins/datatable/js/jquery.dataTables.min.js"></script>
        <script src="{{ asset('/') }}plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
        <script src="{{ asset('/') }}js/table-datatable.js"></script>
        <script type="text/javascript" src="{{ asset('/') }}js/history-transaksi.js"></script>
    @endpush
</x-app-layout>
