<x-app-layout>
    <div class="row">
        <div class="col-xl-8 mx-auto">

            <div class="card">
                <div class="card-body">
                    <div class="border p-3 rounded">
                        <h6 class="mb-0 text-uppercase">{{ __('Point Credit & PPOB') }}</h6>
                        <hr>
                        <form action="{{ route('pulsa-ppob.point.update') }}" method="POST" class="row g-3">
                            @csrf
                            @method('PUT')
                            <div class="col-12">
                                <label class="form-label">{{ __('Type') }}</label>
                                <select name="type" id="type"
                                    class="form-control @error('type') is-invalid @enderror">
                                    <option value="+" {{ $type?->value == '+' ? 'selected' : '' }}>(+)
                                        {{ __('Plus') }}</option>
                                    <option value="%" {{ $type?->value == '%' ? 'selected' : '' }}>(%)
                                        {{ __('Percent') }}
                                    </option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('Amount') }}</label>
                                <input type="text" name="amount" id="amount"
                                    class="form-control @error('amount') is-invalid @enderror"
                                    value="{{ @old('amount', $amount->value) }}">
                                @error('amount')
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
