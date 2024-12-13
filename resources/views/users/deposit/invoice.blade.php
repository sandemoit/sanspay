<x-app-layout>
    <div class="card radius-10">
        <div class="card-header py-3">
            <div class="row align-items-center g-3">
                <div class="col-12 col-lg-6">
                    <h5 class="mb-0">Sans Pay</h5>
                </div>
                <div class="col-12 col-lg-6 text-md-end">
                    @if ($deposit->status == 'pending')
                        <button class="btn btn-danger me-2">Unpaid</button>
                    @elseif ($deposit->status == 'cancel')
                        <button class="btn btn-danger me-2">Canceled</button>
                    @elseif ($deposit->status == 'settlement')
                        <button class="btn btn-success me-2">Paid</button>
                    @endif
                    <a href="javascript:;" onclick="printInvoice()" class="btn btn-secondary"><ion-icon
                            name="print-sharp"></ion-icon>Print</a>
                </div>
            </div>
        </div>
        <div class="card-header py-2">
            <div class="row row-cols-1 row-cols-lg-3">
                <div class="col">
                    <div class="">
                        <small>{{ __('Invoice') }} / {{ $deposit->created_at->format('F') }} period</small>
                        <div class=""><b>{{ $deposit->created_at->format('M d, Y') }}</b></div>
                        <div class="invoice-detail">
                            #{{ $deposit->topup_id }}<br>
                            {{ __('Topup Sans Pay') }}
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="">
                        <h4><b>{{ $method->name }}</b></h4>
                        <h4><b>{{ $method->data }}</b>
                            <a href="javascript:;" class="btn-outline-secondary "
                                onclick="copyToClipboard('{{ $method->data }}')"><ion-icon
                                    name="copy-outline"></ion-icon></a>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">

            <div class="row bg-light align-items-center m-0">
                <div class="col col-auto p-4">
                    <p class="mb-0">{{ __('SUBTOTAL') }}</p>
                    <h4 class="mb-0">{{ nominal($deposit->amount) }}</h4>
                </div>
                <div class="col col-auto me-auto p-4">
                    <p class="mb-0">{{ __('FEE SERVICE') }}</p>
                    <h4 class="mb-0">
                        @if ($deposit->depositMethod->xfee)
                            {{ $deposit->depositMethod->fee }}{{ $deposit->depositMethod->xfee }}
                        @else
                            {{ nominal($deposit->depositMethod->fee) }}
                        @endif
                    </h4>
                </div>
                <div class="col me-auto p-4">
                    <p class="mb-0">{{ __('CODE UNIQUE') }}</p>
                    <h4 class="mb-0">{{ substr($deposit->total_transfer, -3) }}</h4>
                </div>
                <div class="col bg-primary col-auto p-4">
                    <p class="mb-0 text-white">{{ __('TOTAL TRANSFER') }}</p>
                    <h4 class="mb-0 text-white">Rp. {{ nominal($deposit->total_transfer) }}</h4>
                </div>
            </div><!--end row-->

            <hr>
            <!-- begin invoice-note -->
            <div class="my-3">
                @if ($deposit->status == 'pending')
                    @if (
                        !in_array($deposit->payment_method, [
                            'bni_va',
                            'bca_va',
                            'bri_va',
                            'cimb_va',
                            'mandiri_va',
                            'permata_va',
                            'gopay',
                            'shopeepay',
                            'qris',
                        ]))
                        <a href="https://wa.me/{{ configWeb('whatsapp_url')->value }}?text=Confirmation+Deposit+{{ nominal($deposit->amount) }}+{{ $deposit->topup_id }}"
                            class="btn btn-primary btn-sm mb-2" target="_blank">{{ __('Confirmation Deposit') }}</a>
                    @else
                        {{-- <button type="button" data-token="{{ $deposit->snap_token }}" id="pay-button"
                        class="btn btn-primary btn-sm mb-2">{{ __('Pay Now') }}</button> --}}
                        <a href="{{ $deposit->redirect_url }}"
                            class="btn btn-primary btn-sm mb-2">{{ __('Pay Now') }}</a>
                    @endif
                    <a href="{{ route('deposit.cancel', $deposit->topup_id) }}"
                        class="btn btn-danger btn-sm mb-2">Cancel
                @endif

                Deposit</a><br>
                * Payment is due within 30 days<br>
                * If you have any questions concerning this invoice, contact [Name, Phone Number, Email]
            </div>
            <!-- end invoice-note -->
        </div>

        <div class="card-footer py-3 bg-transparent">
            <p class="text-center mb-2">
                THANK YOU FOR YOUR BUSINESS
            </p>
            <p class="text-center d-flex align-items-center gap-3 justify-content-center mb-0">
                <span class=""><i class="bi bi-globe"></i> www.domain.com</span>
                <span class=""><i class="bi bi-telephone-fill"></i> T:+11-0462879</span>
                <span class=""><i class="bi bi-envelope-fill"></i> info@example.com</span>
            </p>
        </div>
    </div>

    @push('custom-js')
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key={{ env('MIDTRANS_CLIENT_KEY') }}"></script>

        <script type="text/javascript">
            document.getElementById('pay-button').onclick = function() {
                var token = $(this).data('token');

                // SnapToken acquired from previous step
                snap.pay(token, {
                    // Optional
                    onSuccess: function(result) {
                        /* You may add your own js here, this is just example */
                        document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    },
                    // Optional
                    onPending: function(result) {
                        /* You may add your own js here, this is just example */
                        document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    },
                    // Optional
                    onError: function(result) {
                        /* You may add your own js here, this is just example */
                        document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    }
                });
            };
        </script>

        <script>
            function printInvoice() {
                window.print();
            }

            function copyToClipboard(text) {
                // Ambil hanya angka dari teks
                const numericText = text.replace(/\D/g, ""); // Hanya angka yang disimpan

                // Buat elemen textarea untuk sementara
                const tempInput = document.createElement("textarea");
                tempInput.value = numericText;
                document.body.appendChild(tempInput);

                // Pilih teks dan salin ke clipboard
                tempInput.select();
                tempInput.setSelectionRange(0, 99999); // Untuk kompatibilitas di perangkat mobile
                document.execCommand("copy");

                // Hapus elemen sementara
                document.body.removeChild(tempInput);

                // Beri notifikasi
                alert("No Rekening suscessfully copied: " + numericText);
            }
        </script>
    @endpush
</x-app-layout>
