<x-app-layout>
    <div class="row">
        <div class="col-xl-8 mx-auto">

            <div class="card">
                <div class="card-body">
                    <div class="border p-3 rounded">
                        <h6 class="mb-0 text-uppercase">{{ __('Configuration Mail') }}</h6>
                        <hr>
                        <form enctype="multipart/form-data" action="{{ route('admin.configure.mail') }}" method="POST"
                            class="row g-3">
                            @csrf
                            @method('PUT')
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="smtp_username" class="form-label">{{ __('SMTP Username') }}</label>
                                    <input type="text" class="form-control" id="smtp_username" name="smtp_username"
                                        value="{{ @old('smtp_username', $smtp_username->value) }}" required>
                                    @error('smtp_username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="smtp_password" class="form-label">{{ __('SMTP Password') }}</label>
                                    <input type="text" class="form-control" id="smtp_password" name="smtp_password"
                                        value="{{ @old('smtp_password', $smtp_password->value) }}" required>
                                    @error('smtp_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="smtp_server" class="form-label">{{ __('SMTP Server') }}</label>
                                    <input type="text" class="form-control" id="smtp_server" name="smtp_server"
                                        value="{{ @old('smtp_server', $smtp_server->value) }}" required>
                                    @error('smtp_server')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="smtp_from" class="form-label">{{ __('SMTP From') }}</label>
                                    <input type="text" class="form-control" id="smtp_from" name="smtp_from"
                                        value="{{ @old('smtp_from', $smtp_from->value) }}" required>
                                    @error('smtp_from')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
