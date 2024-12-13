<x-app-layout>
    <div class="row">
        <div class="col-xl-8 mx-auto">

            <div class="card">
                <div class="card-body">
                    <div class="border p-3 rounded">
                        <h6 class="mb-0 text-uppercase">{{ __('Configuration Notification') }}</h6>
                        <hr>
                        <form action="{{ route('admin.notification') }}" method="POST" class="row g-3">
                            @csrf
                            @method('PUT')
                            <div class="row col-12 mt-3">
                                <div class="row mt-3">
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <label class="form-label">{{ __('Deposit WhatsApp') }}</label>
                                        <textarea name="deposit_wa" id="deposit_wa" class="form-control @error('deposit_wa') is-invalid @enderror"
                                            cols="30" rows="8">{{ @old('deposit_wa', $deposit_wa->value) }}</textarea>
                                        @error('deposit_wa')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <label class="form-label">{{ __('Deposit Email') }}</label>
                                        <textarea name="deposit_email" id="deposit_email" class="form-control @error('deposit_email') is-invalid @enderror"
                                            cols="30" rows="8">{{ @old('deposit_email', $deposit_email->value) }}</textarea>
                                        @error('deposit_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <label class="form-label">{{ __('Transaction WhatsApp') }}</label>
                                        <textarea name="transaction_wa" id="transaction_wa" class="form-control @error('transaction_wa') is-invalid @enderror"
                                            cols="30" rows="8">{{ @old('transaction_wa', $transaction_wa->value) }}</textarea>
                                        @error('transaction_wa')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <label class="form-label">{{ __('Transaction Email') }}</label>
                                        <textarea name="transaction_email" id="transaction_email"
                                            class="form-control @error('transaction_email') is-invalid @enderror" cols="30" rows="8">{{ @old('transaction_email', $transaction_email->value) }}</textarea>
                                        @error('transaction_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
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
</x-app-layout>
