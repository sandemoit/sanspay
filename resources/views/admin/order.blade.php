<x-app-layout>
    @push('custom-css')
        <link href="{{ asset('/') }}plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
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
                            <p class="mb-0 fs-6">{{ __('Profit Agen') }}</p>
                        </div>
                        <div class="ms-auto widget-icon-small text-white bg-gradient-info">
                            <ion-icon name="cash-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <div>
                            <h4 class="mb-0">Rp.{{ nominal($statuses['profit']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr />
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="orders-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Username ') }}</th>
                            <th>{{ __('Product') }}</th>
                            <th>{{ __('Trxid') }}</th>
                            <th>{{ __('Target') }}</th>
                            <th>{{ __('Harga') }}</th>
                            <th>{{ __('Status') }}</th>
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
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="date" class="form-label">{{ __('Date') }}</label>
                        <input type="text" class="form-control" id="date" name="date" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="product" class="form-label">{{ __('Product') }}</label>
                        <input type="text" class="form-control" id="product" name="product" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="target" class="form-label">{{ __('Target') }}</label>
                        <input type="text" class="form-control" id="target" name="target" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="sn" class="form-label">{{ __('Ket/SN') }}</label>
                        <textarea rows="3" id="sn" name="sn" class="form-control" readonly></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="note" class="form-label">{{ __('Note') }}</label>
                        <input type="text" class="form-control" id="note" name="note" readonly>
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
        <script type="text/javascript" src="{{ asset('/') }}js/order.js"></script>
    @endpush
</x-app-layout>
