<x-app-layout>
    <div class="row">
        <div class="col-xl-8 mx-auto">

            <div class="card">
                <div class="card-body">
                    <div class="border p-3 rounded">
                        <h6 class="mb-0 text-uppercase">{{ __('Configuration Website') }}</h6>
                        <hr>
                        <form enctype="multipart/form-data" action="{{ route('admin.configure.website') }}" method="POST"
                            class="row g-3">
                            @csrf
                            @method('PUT')
                            <div class="row col-12 mt-3">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <label class="form-label">{{ __('Title') }}</label>
                                        <input type="text" name="title" id="title"
                                            class="form-control @error('title') is-invalid @enderror"
                                            value="{{ @old('title', $title->value) }}">
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <label class="form-label">{{ __('Short Title') }}</label>
                                        <input type="text" name="short_title" id="short_title"
                                            class="form-control @error('short_title') is-invalid @enderror"
                                            value="{{ @old('short_title', $short_title->value) }}">
                                        @error('short_title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <label class="form-label">{{ __('Logo Image') }}</label>
                                        <div class="input-group">
                                            <input type="file" name="logo" id="logo"
                                                class="form-control @error('logo') is-invalid @enderror"
                                                value="{{ @old('logo', $logo->value) }}"
                                                onchange="document.getElementById('logo-preview').src = window.URL.createObjectURL(this.files[0])">
                                            <span class="input-group-text">
                                                <img id="logo-preview" src="{{ asset($logo->value) }}"
                                                    alt="Logo Preview" width="38" height="38">
                                            </span>
                                        </div>
                                        @error('logo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <label class="form-label">{{ __('Favicon Image') }}</label>
                                        <div class="input-group">
                                            <input type="file" name="favicon" id="favicon"
                                                class="form-control @error('favicon') is-invalid @enderror"
                                                value="{{ @old('favicon', $favicon->value) }}"
                                                onchange="document.getElementById('favicon-preview').src = window.URL.createObjectURL(this.files[0])">
                                            <span class="input-group-text">
                                                <img id="favicon-preview" src="{{ asset($favicon->value) }}"
                                                    alt="Logo Preview" width="38" height="38">
                                            </span>
                                        </div>
                                        @error('favicon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <label class="form-label">{{ __('Web Description') }}</label>
                                        <textarea name="web_description" id="web_description"
                                            class="form-control @error('web_description') is-invalid @enderror" cols="30" rows="6">{{ @old('web_description', $web_description->value) }}</textarea>
                                        @error('web_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <label class="form-label">{{ __('Web Keyword') }}</label>
                                        <textarea name="web_keyword" id="web_keyword" class="form-control @error('web_keyword') is-invalid @enderror"
                                            cols="30" rows="6">{{ @old('web_keyword', $web_keyword->value) }}</textarea>
                                        @error('web_keyword')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <label class="form-label">{{ __('Maintenance') }}</label>
                                    <select name="maintenance" id="maintenance" class="form-select">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
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
