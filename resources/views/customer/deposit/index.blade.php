<x-app-layout>
    @push('custom-css')
        <link href="{{ asset('/') }}plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    @endpush

    <div class="row">
        <div class="col-xl-8 mx-auto">

            <div class="card">
                <div class="card-body">
                    <div class="border p-3 rounded">
                        <h6 class="mb-0 text-uppercase">{{ __('Request Deposit') }}</h6>
                        <hr>
                        <form action="{{ route('deposit.store') }}" method="POST" class="row">
                            @csrf
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Type Payment') }}</label>
                                    <select name="typepayment" id="typepayment" class="form-select">
                                        <option selected disabled>{{ __('Select Type') }}</option>
                                        <option value="0">{{ __('Manual (Non Admin)') }}</option>
                                        <option value="1">{{ __('Otomatis (Admin)') }}</option>
                                    </select>
                                    @error('typepayment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Method Payment') }}</label>
                                    <select name="methodpayment" id="methodpayment" class="form-select">
                                        <option selected disabled">{{ __('Select Method') }}</option>
                                        {{-- option sesuai type --}}
                                    </select>
                                    @error('methodpayment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Nominal Deposit') }}</label>
                                    <input name="nominalDeposit" id="nominalDeposit" class="form-control">
                                    @error('nominalDeposit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-3 mb-3">
                                <label class="form-label">{{ __('Fee') }}</label>
                                <input name="fee" id="fee" class="form-control" readonly>
                            </div>
                            <div class="col-3">
                                <label class="form-label">{{ __('Code Unique') }}</label>
                                <input name="code_unique" id="code_unique" class="form-control" readonly>
                            </div>
                            <div class="col-3">
                                <label class="form-label">{{ __('Total Transfer') }}</label>
                                <input name="total_transfer" id="total_transfer" class="form-control" readonly>
                            </div>
                            <div class="col-3">
                                <label class="form-label">{{ __('Salod Recived') }}</label>
                                <input name="saldo_recived" id="saldo_recived" class="form-control" readonly>
                            </div>
                            <div class="col-12">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">{{ __('Save changes') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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
