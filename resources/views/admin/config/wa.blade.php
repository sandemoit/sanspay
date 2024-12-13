<x-app-layout>
    <div class="row">
        <div class="col-xl-8 mx-auto">

            <div class="card">
                <div class="card-body">
                    <div class="border p-3 rounded">
                        <h6 class="mb-0 text-uppercase">{{ __($title) }}</h6>
                        <hr>
                        <form enctype="multipart/form-data" action="{{ route('admin.configure.wa') }}" method="POST"
                            class="row g-3">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="wa_url" class="form-label">{{ __('WA URL') }}</label>
                                <input type="text" class="form-control" id="wa_url" name="wa_url"
                                    value="{{ @old('wa_url', $wa_url->value) }}" required>
                                @error('wa_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="wa_token" class="form-label">{{ __('WA Token') }}</label>
                                <input type="text" class="form-control" id="wa_token" name="wa_token"
                                    value="{{ @old('wa_token', $wa_token->value) }}" required>
                                @error('wa_token')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">{{ __('Save changes') }}</button>
                                </div>
                            </div>
                        </form>
                        <form action="{{ route('admin.configure.testwa') }}" method="POST" class="row g-3 mt-3">
                            <div class="mb-3">
                                <div class="row">
                                    @csrf
                                    <div class="col-6">
                                        <input type="text" class="form-control" id="wa_number" name="wa_number"
                                            placeholder="Test Send WA (082143*****)" required>
                                    </div>
                                    <div class="col-6">
                                        <button type="submit"
                                            class="btn btn-danger">{{ __('Test WhatsApp') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
