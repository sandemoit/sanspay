<x-app-layout>
    <div class="row">
        <div class="col-xl-8 mx-auto">

            <div class="card">
                <div class="card-body">
                    <div class="border p-3 rounded">
                        <h6 class="mb-0 text-uppercase">{{ __('Profit Credit & PPOB') }}</h6>
                        <hr>
                        <form action="{{ route('pulsa-ppob.profit') }}" method="POST" class="row g-3">
                            @csrf
                            @method('PUT')
                            <div class="col-12">
                                <label class="form-label">{{ __('Admin') }}</label>
                                <input type="text" name="admin" id="admin"
                                    class="form-control @error('admin') is-invalid @enderror"
                                    value="{{ @old('admin', $admin->value) }}">
                                @error('admin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('Mitra') }}</label>
                                <input type="text" name="mitra" id="mitra"
                                    class="form-control @error('mitra') is-invalid @enderror"
                                    value="{{ @old('mitra', $mitra->value) }}">
                                @error('mitra')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('Customer') }}</label>
                                <input type="text" name="customer" id="customer"
                                    class="form-control @error('customer') is-invalid @enderror"
                                    value="{{ @old('customer', $customer->value) }}">
                                @error('customer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
