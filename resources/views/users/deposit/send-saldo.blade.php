<x-app-layout>
    @push('custom-css')
        <link href="{{ asset('/') }}plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    @endpush

    <div class="row">
        <div class="col-lg-7 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-primary text-announcement">
                        <ion-icon name="chevron-forward-outline"></ion-icon>
                        <b>PENTING!</b> Baca Informasi Paling Bawah
                    </div>
                    <hr>
                    <form action="{{ route('send.saldo') }}" method="POST" class="row">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label">{{ __('Username Tujuan') }}</label>
                            <input type="text" class="form-control" id="username" name="username"
                                autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="nominal" class="form-label">{{ __('Nominal Saldo') }}</label>
                            <input type="number" class="form-control" id="nominal" name="nominal" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="pin" class="form-label">{{ __('PIN Transaksi') }}</label>
                            <input type="password" class="form-control" id="pin" name="pin"
                                autocomplete="off">
                        </div>
                        <div class="col-6">
                            <div class="d-grid">
                                <button type="button" id="resetKirimSaldo"
                                    class="btn btn-danger">{{ __('Reset') }}</button>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">{{ __('Deposit Now') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-5 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <p class="fw-bold">Penjelasan Form:</p>
                    <ul>
                        <li><b>Username Tujuan</b></li>
                        <p>Username yang akan Anda kirimi saldo. contoh: marpuah105</p>
                        <li><b>Nominal Saldo</b></li>
                        <p>Jumlah saldo yang akan dikirim. contoh: 100000</p>
                        <li><b>PIN Transaksi</b></li>
                        <p>Masukkan 6 digit PIN transaksi Anda</p>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="kirim-saldo-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>{{ __('Tujuan') }}</th>
                            <th>{{ __('Nominal') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('TrxId') }}</th>
                            <th>{{ __('Pengirim') }}</th>
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

        <script type="text/javascript" src="{{ asset('/') }}js/send-saldo.js"></script>
        <script>
            $(document).on('click', '#resetKirimSaldo', function(e) {
                // Select the form
                const form = document.querySelector('form');

                // Reset all form fields to their default values
                form.reset();

                // Clear all readonly fields
                $('#username').trigger('reset');
                $('#nominal').trigger('reset');
                $('#pin').trigger('reset');
            });
        </script>
    @endpush
</x-app-layout>
