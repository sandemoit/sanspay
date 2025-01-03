<x-app-layout>
    @include('layouts.breadcrumbs')

    <div class="row">
        <div class="col-xl-8 mx-auto">

            <div class="card">
                <div class="card-body">
                    <h6 class="mb-0 text-uppercase text-center">{{ __($title) }}</h6>
                    <hr>
                    <form action="{{ route('upgrade.mitra') }}" method="POST" class="row g-3"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">{{ __('Gender') }}</label>
                                <select name="gender" id="gender" class="form-select">
                                    <option value="Laki - laki">Laki - laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                                @error('no_ktp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('Alamat Sesuai KTP') }}</label>
                                <input type="text" name="full_address" id="full_address"
                                    class="form-control @error('full_address') is-invalid @enderror"
                                    value="{{ @old('full_address') }}">
                                @error('full_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('Nomor eKTP/NIK') }}</label>
                                <input type="text" name="no_ktp" id="no_ktp"
                                    class="form-control @error('no_ktp') is-invalid @enderror"
                                    value="{{ @old('no_ktp') }}">
                                @error('no_ktp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('Selfie + Pegang eKTP') }}</label>
                                <input type="file" name="selfie_ktp" id="selfie_ktp"
                                    class="form-control @error('selfie_ktp') is-invalid @enderror"
                                    value="{{ @old('selfie_ktp') }}">
                                @error('selfie_ktp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
