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
                            name="add-circle-outline"></ion-icon>{{ __('Add Announcement') }}</button>
                </div>
                <table id="announcement-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>{{ __('Title') }}</th>
                            <th style="width: 8%">{{ __('Image') }}</th>
                            <th>{{ __('Slug') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Viewer') }}</th>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">{{ __('Add Announcement') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.announcement.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">{{ __('Title') }}</label>
                            <input type="text" class="form-control" name="title" id="title">
                        </div>
                        <div class="mb-3">
                            <label for="viewer" class="form-label">{{ __('Viewer') }}</label>
                            <select name="viewer" id="viewer" class="form-select">
                                <option value="all">All</option>
                                <option value="admin">Admin</option>
                                <option value="agent">Agent</option>
                                <option value="customer">Customer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">{{ __('Type') }}</label>
                            <select name="type" id="type" class="form-select">
                                <option value="static">Static</option>
                                <option value="banner">Banner</option>
                            </select>
                        </div>
                        <div class="mb-3 d-none">
                            <label for="image" class="form-label">{{ __('Image') }}</label>
                            <input type="file" class="form-control" name="image" id="image">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea class="form-control" name="description" id="description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('custom-js')
        <script src="{{ asset('/') }}plugins/datatable/js/jquery.dataTables.min.js"></script>
        <script src="{{ asset('/') }}plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
        <script src="{{ asset('/') }}js/table-datatable.js"></script>
        <script src="https://cdn.tiny.cloud/1/esu5z25uowjyn5k82a5wt5d72d8cnaj99cywlqyny4km65wi/tinymce/7/tinymce.min.js"
            referrerpolicy="origin"></script>
        <script type="text/javascript" src="{{ asset('/') }}js/announcement.js"></script>
    @endpush
</x-app-layout>
