<x-app-layout>
    <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2 row-cols-xxl-4">
        <div class="col mb-3">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="text-center">
                        <img src="https://digiflazz.com/images/logo/main.png" class="img-fluid mb-3" alt="...">
                        <p class="fs-6 mb-0 font-bold">{{ __($digi->status ?? 'Inactive') }}</p>
                        <h4 class="my-2">Rp. {{ nominal($digi->saldo ?? 0) }}</h4>

                        <div class="d-grid">
                            <button type="button" class="btn btn-info radius-10 mt-3" data-bs-toggle="modal"
                                data-bs-target="#configruasiDigiModal"><ion-icon name="cog-outline"></ion-icon>
                                {{ __('Configuration') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col mb-3">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="text-center">
                        <img src="https://iconape.com/wp-content/files/yh/207674/svg/207674.svg" class="img-fluid mb-3"
                            alt="Midtrans">
                        <p class="fs-6 mb-0 font-bold">{{ __($midtrans->status ?? 'Inactive') }}</p>
                        <h4 class="my-2">Rp. {{ nominal($midtrans->saldo ?? 0) }}</h4>

                        <div class="d-grid">
                            <button type="button" class="btn btn-info radius-10 mt-3" data-bs-toggle="modal"
                                data-bs-target="#configMidtransModal"><ion-icon name="cog-outline"></ion-icon>
                                {{ __('Configuration') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- digi --}}
    <div class="modal fade" id="configruasiDigiModal" tabindex="-1" aria-labelledby="configruasiDigiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="configruasiDigiModalLabel">{{ __('Configuration') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="configDigi">
                        <div class="mb-3">
                            <label for="provider" class="form-label">{{ __('Provider') }}</label>
                            <input type="text" class="form-control" id="provider" name="provider" value="DigiFlazz"
                                required readonly>
                        </div>
                        <div class="mb-3">
                            <label for="code" class="form-label">{{ __('Code') }}</label>
                            <input type="text" class="form-control" id="code" name="code" value="DigiFlazz"
                                required readonly>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">{{ __('Username') }}</label>
                            <input type="text" class="form-control" id="username" name="username"
                                value="{{ $digi->username ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="product_api_key" class="form-label">{{ __('Pro Api Key') }}</label>
                            <input type="text" class="form-control" id="product_api_key" name="product_api_key"
                                value="{{ $digi->product_api_key ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="development_api_key" class="form-label">{{ __('Dev Api Key') }}</label>
                            <input type="text" class="form-control" id="development_api_key"
                                name="development_api_key" value="{{ $digi->development_api_key ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">{{ __('Status') }}</label>
                            <select name="status" id="status" class="form-select" required>
                                <option selected value="active">{{ __('Active') }}</option>
                                <option value="inactive">{{ __('InActive') }}</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" id="submitConfig"
                        class="btn btn-primary">{{ __('Save changes') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- midtrans --}}
    <div class="modal fade" id="configMidtransModal" tabindex="-1" aria-labelledby="configMidtransModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="configMidtransModalLabel">{{ __('Configuration') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="configMidtrans">
                        <div class="mb-3">
                            <label for="provider" class="form-label">{{ __('Provider') }}</label>
                            <input type="text" class="form-control" id="provider" name="provider"
                                value="Midtrans" required readonly>
                        </div>
                        <div class="mb-3">
                            <label for="code" class="form-label">{{ __('Code') }}</label>
                            <input type="text" class="form-control" id="code" name="code"
                                value="Midtrans" required readonly>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">{{ __('Username') }}</label>
                            <input type="text" class="form-control" id="username" name="username"
                                value="{{ $midtrans->username ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="product_api_key" class="form-label">{{ __('Pro Api Key') }}</label>
                            <input type="text" class="form-control" id="product_api_key" name="product_api_key"
                                value="{{ $midtrans->product_api_key ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="development_api_key" class="form-label">{{ __('Dev Api Key') }}</label>
                            <input type="text" class="form-control" id="development_api_key"
                                name="development_api_key" value="{{ $midtrans->development_api_key ?? '' }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">{{ __('Status') }}</label>
                            <select name="status" id="status" class="form-select" required>
                                <option selected value="active">{{ __('Active') }}</option>
                                <option value="inactive">{{ __('InActive') }}</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" id="submitConfigMidtrans"
                        class="btn btn-primary">{{ __('Save changes') }}</button>
                </div>
            </div>
        </div>
    </div>

    @push('custom-js')
        <script type="text/javascript">
            // config digi
            $(document).ready(function() {
                $('#submitConfig').click(function(e) {
                    e.preventDefault();

                    // Tampilkan animasi loading pada tombol submit
                    let submitButton = $(this);
                    submitButton.prop('disabled', true).text('Loading...');

                    // Kirim request AJAX
                    $.ajax({
                        url: 'configDigiFlazz',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: $('#configDigi').serialize(),
                        success: function(response) {
                            submitButton.prop('disabled', false).text('Save changes');

                            if (response.result) {
                                // Notifikasi sukses dengan SweetAlert
                                toastr.success(response.message, {
                                    timeOut: 2000,
                                    onHidden: () => {
                                        location.reload();
                                    }
                                });
                            } else {
                                // Notifikasi error dengan SweetAlert
                                toastr.error(response.message, {
                                    timeOut: 2000
                                });
                            }
                        },
                        error: function(xhr) {
                            submitButton.prop('disabled', false).text('Save changes');
                            let errorMessage = xhr.responseJSON && xhr.responseJSON.data.message ?
                                xhr
                                .responseJSON.data.message : 'An error occurred';
                            toastr.error(errorMessage, {
                                timeOut: 2000
                            });
                        }
                    });
                });
            });

            // config midtrans
            $(document).ready(function() {
                $('#submitConfigMidtrans').click(function(e) {
                    e.preventDefault();

                    // Tampilkan animasi loading pada tombol submit
                    let submitButton = $(this);
                    submitButton.prop('disabled', true).text('Loading...');

                    // Kirim request AJAX
                    $.ajax({
                        url: 'configMidtrans',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: $('#configMidtrans').serialize(),
                        success: function(response) {
                            submitButton.prop('disabled', false).text('Save changes');

                            if (response.result) {
                                // Notifikasi sukses dengan SweetAlert
                                toastr.success(response.message, {
                                    timeOut: 2000
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                // Notifikasi error dengan SweetAlert
                                toast.error(response.message, {
                                    timeOut: 2000
                                });
                            }
                        },
                        error: function(xhr) {
                            submitButton.prop('disabled', false).text('Save changes');
                            let errorMessage = xhr.responseJSON && xhr.responseJSON.data.message ?
                                xhr
                                .responseJSON.data.message : 'An error occurred';
                            toastr.error(errorMessage, {
                                timeOut: 2000
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
