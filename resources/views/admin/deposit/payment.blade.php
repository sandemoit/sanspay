<x-app-layout>
    @push('custom-css')
        <link href="{{ asset('/') }}plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    @endpush

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <div class="mb-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addModal"><ion-icon
                            name="add-circle-outline"></ion-icon>{{ __('Add Payment') }}</button>
                </div>
                <table id="paymentDeposit-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>{{ __('Code') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        {{-- here to fetch data --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- add modal --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">{{ __('Add Method') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('deposit.payment.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="code" class="form-label">{{ __('Code') }}</label>
                            <input type="text" class="form-control" name="code" id="code">
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input type="text" class="form-control" name="name" id="name">
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">{{ __('Type Payment') }}</label>
                            <select class="form-select" id="type" name="type">
                                <option value="va">Virtual Account</option>
                                <option value="cstore">Ritel</option>
                                <option value="gopay">Gopay</option>
                                <option value="other_qris">Qris</option>
                                <option value="shopeepay">Shopee Pay</option>
                                <option value="kredivo">Kredivo</option>
                                <option value="akulaku">Akulaku</option>
                                <option value="bank">Bank (Manual)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('custom-js')
        <script src="{{ asset('/') }}plugins/datatable/js/jquery.dataTables.min.js"></script>
        <script src="{{ asset('/') }}plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
        <script src="{{ asset('/') }}js/table-datatable.js"></script>
        <script type="text/javascript" src="{{ asset('/') }}js/deposit.js"></script>
    @endpush
</x-app-layout>
