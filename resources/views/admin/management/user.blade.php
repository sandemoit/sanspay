<x-app-layout>
    @push('custom-css')
        <link href="{{ asset('/') }}plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    @endpush
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="users-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>{{ __('Nama Lengkap') }}</th>
                            <th>{{ __('Saldo') }}</th>
                            <th>{{ __('Username') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Gender') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Bergabung') }}</th>
                            <th>{{ __('Verifikasi') }}</th>
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

    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">{{ __('Add Method') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('deposit.method.store') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div id="modalContent"></div>
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
        <script>
            $(document).ready(function() {
                // Inisialisasi DataTable
                $('#users-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: 'getUser',
                    columns: [{
                            data: null,
                            name: 'no',
                            orderable: true,
                            searchable: false,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: 'fullname',
                            name: 'fullname',
                            orderable: true,
                            searchable: true,
                        },
                        {
                            data: 'saldo',
                            name: 'saldo',
                            orderable: true,
                            searchable: true,
                        },
                        {
                            data: 'name',
                            name: 'name',
                            orderable: true,
                            searchable: true,
                        },
                        {
                            data: 'email',
                            name: 'email',
                            orderable: true,
                            searchable: true,
                        },
                        {
                            data: 'gender',
                            name: 'gender',
                            orderable: true,
                            searchable: true,
                        },
                        {
                            data: 'role_user',
                            name: 'role_user',
                            orderable: true,
                            searchable: true,
                        },
                        {
                            data: 'status_user',
                            name: 'status_user',
                            orderable: true,
                            searchable: true,
                        },
                        {
                            data: 'gabung',
                            name: 'gabung',
                            orderable: true,
                            searchable: true,
                        },
                        {
                            data: 'terverifikasi',
                            name: 'terverifikasi',
                            orderable: true,
                            searchable: true,
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: true,
                            searchable: true,
                        },
                    ]
                });
            });
        </script>

        <script>
            $(document).on('click', '.block-user', function(e) {
                e.preventDefault();

                // Ambil URL dari href
                let url = $(this).attr('href');

                Swal.fire({
                    title: "Apakah anda yakin?",
                    text: "User akan di blokir!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, blokir!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // AJAX request to delete
                        $.ajax({
                            url: url, // Menggunakan URL dari href tombol delete
                            type: 'GET',
                            success: function(response) {
                                // Tampilkan pesan berhasil
                                toastr.success(response.success, {
                                    timeOut: 2000
                                })

                                // Refresh DataTable atau hapus baris secara manual
                                $('#users-table').DataTable().ajax.reload();
                            },
                            error: function(xhr) {
                                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ?
                                    xhr.responseJSON.message : 'An error occurred';
                                toastr.error(errorMessage, {
                                    timeOut: 2000
                                })
                            }
                        });
                    }
                });
            });
            $(document).on('click', '.unblock-user', function(e) {
                e.preventDefault();

                // Ambil URL dari href
                let url = $(this).attr('href');

                Swal.fire({
                    title: "Apakah anda yakin?",
                    text: "User akan di unblokir!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, unblokir!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // AJAX request to delete
                        $.ajax({
                            url: url, // Menggunakan URL dari href tombol delete
                            type: 'GET',
                            success: function(response) {
                                // Tampilkan pesan berhasil
                                toastr.success(response.success, {
                                    timeOut: 2000
                                })

                                // Refresh DataTable atau hapus baris secara manual
                                $('#users-table').DataTable().ajax.reload();
                            },
                            error: function(xhr) {
                                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ?
                                    xhr.responseJSON.message : 'An error occurred';
                                toastr.error(errorMessage, {
                                    timeOut: 2000
                                })
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
