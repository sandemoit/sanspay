<x-app-layout>
    @push('custom-css')
        <link href="{{ asset('/') }}plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    @endpush

    <div class="row row-cols-1 row-cols-lg-2 row-cols-xxl-4">
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-2">
                        <div>
                            <p class="mb-0 fs-6">Transaksi Sukses</p>
                        </div>
                        <div class="ms-auto widget-icon-small text-white bg-gradient-success">
                            <ion-icon name="checkmark-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <div>
                            <h4 class="mb-0">0</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-2">
                        <div>
                            <p class="mb-0 fs-6">Transaksi Diproses</p>
                        </div>
                        <div class="ms-auto widget-icon-small text-white bg-gradient-info">
                            <ion-icon name="hourglass-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <div>
                            <h4 class="mb-0">0</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-2">
                        <div>
                            <p class="mb-0 fs-6">Transaksi Pending</p>
                        </div>
                        <div class="ms-auto widget-icon-small text-white bg-gradient-warning">
                            <ion-icon name="timer-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <div>
                            <h4 class="mb-0">0</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-2">
                        <div>
                            <p class="mb-0 fs-6">Transaksi Dibatalkan</p>
                        </div>
                        <div class="ms-auto widget-icon-small text-white bg-gradient-danger">
                            <ion-icon name="close-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <div>
                            <h4 class="mb-0">0</h4>
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
                            <th>Tanggal</th>
                            <th>Username</th>
                            <th>Product</th>
                            <th>Target</th>
                            <th>Total Payment</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        {{-- here to fetch data --}}
                    </tbody>
                </table>
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