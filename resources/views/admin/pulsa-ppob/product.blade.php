<x-app-layout>
    @push('custom-css')
        <link href="{{ asset('/') }}plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    @endpush

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <div class="mb-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addModal"><ion-icon
                            name="add-circle-outline"></ion-icon>{{ __('Add Product') }}</button>
                    <a href="{{ route('pulsa-ppob.product.pull') }}" id="pullProductBtn"
                        class="btn btn-success"><ion-icon
                            name="cloud-download-outline"></ion-icon>{{ __('Pull Product') }}</a>
                    <a href="{{ route('pulsa-ppob.product.deleteAll') }}" id="delProductBtn"
                        class="btn btn-danger"><ion-icon name="trash-outline"></ion-icon>{{ __('Delete All') }}</a>
                </div>
                <table id="products-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Code') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('Provider') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        {{-- here to fetch data --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- add modal --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">{{ __('Add Product') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="provider" class="form-label">{{ __('Provider') }}</label>
                                    <select name="provider" id="provider" class="form-select" required>
                                        <option selected disabled>{{ __('Select Provider') }}</option>
                                        <option value="DigiFlazz">DIGI FLAZZ</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">{{ __('Code') }}</label>
                                    <input type="text" class="form-control" id="code" name="code" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">{{ __('Type') }}</label>
                            <select name="type" id="type" class="form-select" required>
                                <option selected disabled>{{ __('Select Type') }}</option>
                                @foreach ($type as $value)
                                    <option value="{{ $value->real }}">{{ $value->real }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="brand" class="form-label">{{ __('Brand') }}</label>
                            <select name="brand" id="brand" class="form-select" required>
                                <option selected disabled>{{ __('Select Brand') }}</option>
                                {{-- option sesuai dari type --}}
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">{{ __('Note') }}</label>
                            <textarea type="text" class="form-control" id="note" name="note" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">{{ __('Price') }}</label>
                            <input type="text" class="form-control" name="price" id="price"required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">{{ __('Status') }}</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="available">{{ __('Available') }}</option>
                                <option value="empty">{{ __('Empty') }}</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-primary">{{ __('Save changes') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">{{ __('Edit Product') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProductForm">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="provider" class="form-label">{{ __('Provider') }}</label>
                                    <select name="provider" id="provider" class="form-select" required>
                                        <option selected disabled>{{ __('Select Provider') }}</option>
                                        <option value="DigiFlazz">DIGI FLAZZ</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">{{ __('Code') }}</label>
                                    <input type="text" class="form-control" id="code" name="code"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">{{ __('Type') }}</label>
                            <select name="type" id="type" class="form-select" required>
                                <option selected disabled>{{ __('Select Type') }}</option>
                                @foreach ($type as $value)
                                    <option value="{{ $value->real }}">{{ $value->real }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="brand" class="form-label">{{ __('Brand') }}</label>
                            <select name="brand" id="brand" class="form-select" required>
                                <option selected disabled>{{ __('Select Brand') }}</option>
                                {{-- option sesuai dari type --}}
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">{{ __('Note') }}</label>
                            <textarea type="text" class="form-control" id="note" name="note" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">{{ __('Price') }}</label>
                            <input type="text" class="form-control" name="price" id="price"required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">{{ __('Status') }}</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="available">{{ __('Available') }}</option>
                                <option value="empty">{{ __('Empty') }}</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-primary">{{ __('Save changes') }}</button>
                </div>
            </div>
        </div>
    </div>

    @push('custom-js')
        <script src="{{ asset('/') }}plugins/datatable/js/jquery.dataTables.min.js"></script>
        <script src="{{ asset('/') }}plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
        <script src="{{ asset('/') }}js/table-datatable.js"></script>
        <script type="text/javascript" src="{{ asset('/') }}js/product.js"></script>
    @endpush
</x-app-layout>
