<x-app-layout>
    @push('custom-css')
        <link href="{{ asset('/') }}plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
        <style>
            /* CSS yang sudah ada */
        </style>
    @endpush

    <div class="row row-cols-1 row-cols-lg-2 row-cols-xxl-3">
        <div class="col mb-3">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-2">
                        <div>
                            <p class="mb-0 fs-6">{{ __('Total Mutasi Masuk') }}</p>
                        </div>
                        <div class="ms-auto widget-icon-small text-white bg-gradient-success">
                            <ion-icon name="add-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <div>
                            <h4 class="mb-0">Rp {{ nominal($totalMasuk) }}</h4>
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
                            <p class="mb-0 fs-6">{{ __('Total Mutasi Keluar') }}</p>
                        </div>
                        <div class="ms-auto widget-icon-small text-white bg-gradient-danger">
                            <ion-icon name="remove-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <div>
                            <h4 class="mb-0">Rp {{ nominal($totalKeluar) }}</h4>
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
                            <p class="mb-0 fs-6">{{ __('Sisa Saldo') }}</p>
                        </div>
                        <div class="ms-auto widget-icon-small text-white bg-gradient-info">
                            <ion-icon name="wallet-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <div>
                            <h4 class="mb-0">Rp {{ nominal($sisaSaldo) }}</h4>
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
                <table id="mutation-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Note') }}</th>
                            <th>{{ __('Tanggal') }}</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @foreach ($mutasi as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if ($item->type == '+')
                                        Masuk
                                    @elseif ($item->type == '-')
                                        Keluar
                                    @else
                                        Point Exchange
                                    @endif
                                </td>
                                <td>Rp {{ nominal($item->amount) }}</td>
                                <td>{{ $item->note }}</td>
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
                $('#mutation-table').DataTable();

                $('#filter').click(function() {
                    var start_date = $('#start_date').val();
                    var end_date = $('#end_date').val();
                    window.location = "{{ route('laporan.mutation') }}?start_date=" + start_date +
                        "&end_date=" + end_date;
                });
            });
        </script>
    @endpush
</x-app-layout>
