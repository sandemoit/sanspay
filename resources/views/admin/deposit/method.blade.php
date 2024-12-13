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
                            name="add-circle-outline"></ion-icon>{{ __('Add Method') }}</button>
                </div>
                <table id="methodDeposit-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>{{ __('Method') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Rate') }}</th>
                            <th>{{ __('Fee') }}</th>
                            <th>{{ __('Minimal') }}</th>
                            <th>{{ __('Description') }}</th>
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
                <form action="{{ route('deposit.method.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="midtrans" class="form-label">{{ __('Midtrans?') }}</label>
                            <select name="midtrans" id="midtrans" class="form-select" required>
                                <option value="0">{{ __('No') }}</option>
                                <option value="1">{{ __('Yes') }}</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="code" class="form-label">{{ __('Select Payment') }}</label>
                            <select name="code" id="code" class="form-select" id="single-select-field"
                                data-placeholder="Choose one thing" required>
                                @foreach ($payments as $payment)
                                    <option value="{{ $payment->code }}">{{ $payment->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="accountname" class="form-label">{{ __('Account Name') }}</label>
                                <input type="text" class="form-control" id="accountname" name="accountname">
                            </div>
                            <div class="col-6">
                                <label for="accountnumber" class="form-label">{{ __('Account Number') }}</label>
                                <input type="text" class="form-control" id="accountnumber" name="accountnumber">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-3">
                                <label for="xfee" class="form-label">{{ __('Fee Type') }}</label>
                                <select class="form-select" id="xfee" name="xfee">
                                    <option value="-">-</option>
                                    <option value="%">%</option>
                                </select>
                            </div>
                            <div class="col-5">
                                <label for="fee" class="form-label">{{ __('Fee Amount') }}</label>
                                <input type="text" class="form-control" id="fee" name="fee"
                                    placeholder="1000">
                            </div>
                            <div class="col-4">
                                <label for="rate" class="form-label">{{ __('Rate') }}</label>
                                <input type="text" class="form-control" id="rate" name="rate"
                                    placeholder="100%">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="minDeposit" class="form-label">{{ __('Minimal Deposit') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp.</span>
                                <input type="text" class="form-control" name="minDeposit" id="minDeposit">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="depositType" class="form-label">{{ __('Deposit Type') }}</label>
                            <select class="form-select" id="depositType" name="depositType">
                                <option value="0">Manual</option>
                                <option value="1">Automatic</option>
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

    {{-- edit modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">{{ __('Edit Method') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editMethodForm" action="{{ route('deposit.method.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="idMethod" id="idMethod">
                        <div class="mb-3">
                            <label for="midtrans" class="form-label">{{ __('Midtrans?') }}</label>
                            <select name="midtrans" id="midtrans" class="form-select" required>
                                <option value="0">{{ __('No') }}</option>
                                <option value="1">{{ __('Yes') }}</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="code" class="form-label">{{ __('Select Payment') }}</label>
                            <select name="code" id="code" class="form-select" id="single-select-field"
                                data-placeholder="Choose one thing" required>
                                @foreach ($payments as $payment)
                                    <option value="{{ $payment->code }}">{{ $payment->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="accountname" class="form-label">{{ __('Account Name') }}</label>
                                <input type="text" class="form-control" id="accountname" name="accountname">
                            </div>
                            <div class="col-6">
                                <label for="accountnumber" class="form-label">{{ __('Account Number') }}</label>
                                <input type="text" class="form-control" id="accountnumber" name="accountnumber">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-3">
                                <label for="xfee" class="form-label">{{ __('Fee Type') }}</label>
                                <select class="form-select" id="xfee" name="xfee">
                                    <option value="-">-</option>
                                    <option value="%">%</option>
                                </select>
                            </div>
                            <div class="col-5">
                                <label for="fee" class="form-label">{{ __('Fee Amount') }}</label>
                                <input type="text" class="form-control" id="fee" name="fee"
                                    placeholder="1000">
                            </div>
                            <div class="col-4">
                                <label for="rate" class="form-label">{{ __('Rate') }}</label>
                                <input type="text" class="form-control" id="rate" name="rate"
                                    placeholder="100%">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="minDeposit" class="form-label">{{ __('Minimal Deposit') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp.</span>
                                <input type="text" class="form-control" name="minDeposit" id="minDeposit">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="depositType" class="form-label">{{ __('Deposit Type') }}</label>
                            <select class="form-select" id="depositType" name="depositType">
                                <option value="0">Manual</option>
                                <option value="1">Automatic</option>
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
