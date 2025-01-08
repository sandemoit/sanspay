$(document).ready(function() {
    $('#upgrade-mitra-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/getUpgradeMitra',
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
                data: 'date',
                name: 'date',
                orderable: true, 
            },
            {
                data: 'user_name',
                name: 'user_name',
                orderable: true, 
            },
            {
                data: 'full_address',
                name: 'full_address',
                orderable: true, 
            },
            {
                data: 'gender',
                name: 'gender',
                orderable: true, 
            },
            {
                data: 'status_case',
                name: 'status_case',
                orderable: true, 
            },
            {
                data: 'action',
                name: 'action',
                orderable: true, 
            },
        ]
    });
});

$(document).on('click', '.btn-detail', function () {
    var id = $(this).data('id'); // Ambil ID dari tombol
    $.ajax({
        url: '/pengajuan/detail/' + id, // Endpoint untuk mengambil data
        type: 'GET',
        success: function (data) {
            // Isi modal dengan data yang didapat dari server
            $('#modalContent').html(`
                <div class="mb-3">
                    <label for="nik" class="form-label">NIK</label>
                    <input type="text" class="form-control" id="nik" value="${data.no_ktp}" readonly>
                </div>
                <div class="mb-3">
                    <label for="selfie_ktp" class="form-label">Selfie KTP</label>
                    <img src="${data.selfie_ktp}" class="img-fluid" alt="Selfie KTP">
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status">
                        <option value="pending" ${data.status === 'pending' ? 'selected' : ''}>Pending</option>
                        <option value="accept" ${data.status === 'accept' ? 'selected' : ''}>Approved</option>
                        <option value="decline" ${data.status === 'decline' ? 'selected' : ''}>Rejected</option>
                    </select>
                </div>
            `);
        },
        error: function (xhr) {
            console.error(xhr.responseText); // Debugging jika ada error
        }
    });
});

$('#detailModal').on('submit', 'form', function (e) {
    e.preventDefault();
    var id = $('.btn-detail').data('id'); // ID yang sedang diubah
    var status = $('#status').val(); // Ambil status yang dipilih

    $.ajax({
        url: '/pengajuan/update-status/' + id,
        type: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        data: {
            status: status
        },
        success: function (data) {
            $('#detailModal').modal('hide'); // Tutup modal
            toastr.success(data.message, {timeOut: 1000});
            $('#upgrade-mitra-table').DataTable().ajax.reload(); // Reload datatable
        },
        error: function (xhr) {
            console.error(xhr.responseText); // Debugging jika error
        }
    });
});