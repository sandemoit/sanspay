<x-app-layout>
    @push('custom-css')
        <link href="{{ asset('/') }}plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    @endpush

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="historyDeposit-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>{{ __('INV & Date') }}</th>
                            <th>{{ __('Method') }}</th>
                            <th>{{ __('Total Transfer') }}</th>
                            <th>{{ __('Saldo Recived') }}</th>
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

    @push('custom-js')
        <script src="{{ asset('/') }}plugins/datatable/js/jquery.dataTables.min.js"></script>
        <script src="{{ asset('/') }}plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
        <script src="{{ asset('/') }}js/table-datatable.js"></script>

        <script type="text/javascript" src="{{ asset('/') }}js/deposit_cust.js"></script>
    @endpush
</x-app-layout>
