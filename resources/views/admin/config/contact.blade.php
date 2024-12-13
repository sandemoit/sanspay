<x-app-layout>
    <div class="row">
        <div class="col-xl-8 mx-auto">

            <div class="card">
                <div class="card-body">
                    <div class="border p-3 rounded">
                        <h6 class="mb-0 text-uppercase">{{ __('Configuration Contact') }}</h6>
                        <hr>
                        <form enctype="multipart/form-data" action="{{ route('admin.configure.contact') }}" method="POST"
                            class="row g-3">
                            @csrf
                            @method('PUT')
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="whatsapp_url" class="form-label">{{ __('Whatsapp') }}</label>
                                    <input type="text" class="form-control" id="whatsapp_url" name="whatsapp_url"
                                        value="{{ @old('whatsapp_url', $whatsapp_url->value) }}" required>
                                    @error('whatsapp_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="instagram_url" class="form-label">{{ __('Instagram') }}</label>
                                    <input type="text" class="form-control" id="instagram_url" name="instagram_url"
                                        value="{{ @old('instagram_url', $instagram_url->value) }}" required>
                                    @error('instagram_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="telegram_url" class="form-label">{{ __('Telegram') }}</label>
                                    <input type="text" class="form-control" id="telegram_url" name="telegram_url"
                                        value="{{ @old('telegram_url', $telegram_url->value) }}" required>
                                    @error('telegram_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="facebook_url" class="form-label">{{ __('Facebook') }}</label>
                                    <input type="text" class="form-control" id="facebook_url" name="facebook_url"
                                        value="{{ @old('facebook_url', $facebook_url->value) }}" required>
                                    @error('facebook_url')
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
