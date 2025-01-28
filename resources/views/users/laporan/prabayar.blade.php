<x-app-layout>
    @push('custom-css')
        <link href="{{ asset('/') }}plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
        <style>
            /* CSS yang sudah ada */
        </style>
    @endpush

    <div class="row row-cols-1 row-cols-lg-2 row-cols-xxl-3 ">
        <div class="col mb-3">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-2">
                        <div>
                            <p class="mb-0 fs-6">{{ __('Total Transaksi Sukses') }}</p>
                        </div>
                        <div class="ms-auto widget-icon-small text-white bg-gradient-success">
                            <ion-icon name="checkmark-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <div>
                            <h4 class="mb-0">{{ $totalSukses }}</h4>
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
                            <p class="mb-0 fs-6">{{ __('Total Transaksi Gagal') }}</p>
                        </div>
                        <div class="ms-auto widget-icon-small text-white bg-gradient-danger">
                            <ion-icon name="close-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <div>
                            <h4 class="mb-0">{{ $totalGagal }}</h4>
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
                            <p class="mb-0 fs-6">{{ __('Total Penjualan') }}</p>
                        </div>
                        <div class="ms-auto widget-icon-small text-white bg-gradient-info">
                            <ion-icon name="cash-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <div>
                            <h4 class="mb-0">Rp {{ nominal($totalPenjualan) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="date" id="start_date" class="form-control" placeholder="Start Date"
                        value="{{ request('start_date', date('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <input type="date" id="end_date" class="form-control" placeholder="End Date"
                        value="{{ request('end_date', date('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <button id="filter" class="btn btn-primary">Filter</button>
                </div>
            </div>
            <div class="table-responsive">
                <table id="prabayar-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>{{ __('Order ID') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Tujuan') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Harga') }}</th>
                            <th>{{ __('Tanggal') }}</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @foreach ($data as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><a href="{{ route('laporan.prabayar.detail', $item->id_order) }}">{{ $item->id_order }}
                                        <ion-icon name="paper-plane-outline"></ion-icon></a>
                                </td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->data }}</td>
                                <td>{{ $item->status }}</td>
                                <td>Rp {{ nominal($item->price) }}</td>
                                <td>{{ tanggal($item->created_at) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('custom-js')
        <script src="{{ asset('/') }}plugins/datatable/js/jquery.dataTables.min.js"></script>
        <script src="{{ asset('/') }}plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#prabayar-table').DataTable();

                $('#filter').click(function() {
                    var start_date = $('#start_date').val();
                    var end_date = $('#end_date').val();
                    window.location = "{{ route('laporan.prabayar') }}?start_date=" + start_date +
                        "&end_date=" + end_date;
                });
            });
        </script>
    @endpush
</x-app-layout>
