<x-app-layout>
    <div class="row">
        <div class="col-lg-7 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-primary text-announcement">
                        <ion-icon name="chevron-forward-outline"></ion-icon>
                        <b>PENTING!</b> Baca Informasi Paling Bawah
                    </div>
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
                                    <label class="form-label">{{ __('Deposit Nominal') }}</label>
                                    {{-- <div class="row row-cols-auto mb-3">
                                        <div class="col">
                                            <button type="button" class="btn btn-outline-secondary px-5"
                                                onclick="setNominal(25000)">25.000</button>
                                        </div>
                                        <div class="col">
                                            <button type="button" class="btn btn-outline-secondary px-5"
                                                onclick="setNominal(50000)">50.000</button>
                                        </div>
                                        <div class="col">
                                            <button type="button" class="btn btn-outline-secondary px-5"
                                                onclick="setNominal(100000)">100.000</button>
                                        </div>
                                        <div class="col">
                                            <button type="button" class="btn btn-outline-secondary px-5"
                                                onclick="setNominal(150000)">150.000</button>
                                        </div>
                                        <div class="col">
                                            <button type="button" class="btn btn-outline-secondary px-5"
                                                onclick="setNominal(200000)">200.000</button>
                                        </div>
                                    </div> --}}
                                    <input name="nominalDeposit" id="nominalDeposit" class="form-control">
                                    <div id="nominalWarning" class="text-danger" style="display: none;">Nominal harus
                                        minimal <span id="minimumValue"></span></div>
                                    @error('nominalDeposit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 mb-3">
                                <label class="form-label">{{ __('Fee') }}</label>
                                <input name="fee" id="fee" class="form-control" readonly>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 mb-3">
                                <label class="form-label">{{ __('Total Transfer') }}</label>
                                <input name="total_transfer" id="total_transfer" class="form-control" readonly>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 mb-3">
                                <label class="form-label">{{ __('Saldo Recived') }}</label>
                                <input name="saldo_recived" id="saldo_recived" class="form-control" readonly>
                            </div>
                            <div class="col-6">
                                <div class="d-grid">
                                    <button type="button" id="resetDeposit"
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
        </div>
        <div class="col-lg-5 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <p class="fw-bold">Penting:</p>
                    <ul>
                        <li>Pastikan jumlah transfer sesuai; jika tidak, akan dikenakan <span
                                class="text-danger">potongan
                                10%</span> <b>(PASTIKAN JUMLAH BENAR)</b></li>
                        <li>Anda hanya dapat memiliki maksimal 1 permintaan deposit yang Tertunda. Hindari melakukan
                            <i>spam</i> dan segera selesaikan pembayaran.
                        </li>
                        <li>Jika permintaan deposit tidak dibayar dalam waktu lebih dari 24 jam, maka permintaan
                            tersebut
                            akan dibatalkan secara otomatis.</li>
                        <li>Saldo akan ditambahkan setelah deposit berhasil diproses.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @push('custom-js')
        <script type="text/javascript" src="{{ asset('/') }}js/deposit_cust.js"></script>
        <script>
            function setNominal(value) {
                $('#nominalDeposit').val(value);
            }
        </script>
    @endpush
</x-app-layout>
