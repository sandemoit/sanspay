// table management deposit
$(document).ready(function() {
    $('#deposit-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: 'management/get',
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
                data: 'name',
                name: 'name',
                orderable: true,
            },
            {
                data: 'payment_method',
                name: 'payment_method',
                orderable: true,
            },
            {
                data: 'amount',
                name: 'amount',
                orderable: true,
            },
            {
                data: 'total_transfer',
                name: 'total_transfer',
                orderable: true,
            },
            {
                data: 'tanggal',
                name: 'tanggal',
                orderable: true,
            },
            {
                data: 'action',
                name: 'action',
                orderable: true,
            },
        ]
    });

    $('#methodDeposit-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: 'methode/get',
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
                data: 'code',
                name: 'code',
                orderable: true,
            },
            {
                data: 'name',
                name: 'name',
                orderable: true,
            },
            {
                data: 'rate_method',
                name: 'rate_method',
                orderable: true,
            },
            {
                data: 'fee_method',
                name: 'fee_method',
                orderable: true,
            },
            {
                data: 'min_order',
                name: 'min_order',
                orderable: true,
            },
            {
                data: 'data',
                name: 'data',
                orderable: true,
            },
            {
                data: 'action',
                name: 'action',
                orderable: true,
            },
        ]
    });

    $('#paymentDeposit-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: 'payment/get',
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
                data: 'code',
                name: 'code',
                orderable: true,
            },
            {
                data: 'name',
                name: 'name',
                orderable: true,
            },
            {
                data: 'type',
                name: 'type',
                orderable: true,
            },
            {
                data: 'action',
                name: 'action',
                orderable: true,
            },
        ]
    });

    $('#methodDeposit-table').on('click', '.edit-btn', function() {
        var id = $(this).data('id'); // Ambil ID dari tombol edit

        // AJAX untuk mengambil data dari backend
        $.ajax({
            url: '/admin/deposit/methode/get/' + id, // Route ke method backend
            type: 'GET',
            success: function(response) {
                // Set data pada form modal dengan response dari server
                $('#editMethodForm #idMethod').val(response.idMethod); // Set ID method di modal
                $('#editMethodForm #code').val(response.code).change();
                $('#editMethodForm #accountname').val(response.accountname);
                $('#editMethodForm #accountnumber').val(response.accountnumber);
                $('#editMethodForm #xfee').val(response.xfee).change();
                $('#editMethodForm #fee').val(response.fee);
                $('#editMethodForm #rate').val(response.rate);
                $('#editMethodForm #minDeposit').val(response.minDeposit);
                $('#editMethodForm #depositType').val(response.depositType).change();
                $('#editMethodForm #midtrans').val(response.midtrans).change();

                // Tampilkan modal edit setelah data berhasil di-load
                $('#editModal').modal('show');
            },
            error: function(xhr) {
                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred';
                toastr.error(errorMessage, { timeOut: 2000 });
            }
        });
    });
});

// btn accept/decline
$(document).on('click', '.accept-btn, .decline-btn', function (e) {
    e.preventDefault();
    
    // Ambil URL dari href
    let url = $(this).attr('href');
    let actionText = $(this).hasClass('accept-btn') ? "accept" : "decline";
    let confirmText = actionText === "accept" ? "Yes, accept deposit!" : "Yes, decline deposit!";

    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: confirmText
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX request untuk update status deposit
            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    // Tampilkan pesan berhasil
                    toastr.success(response.message, {timeOut: 2000});

                    // Refresh DataTable atau hapus baris secara manual
                    $('#deposit-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred';
                    Swal.fire({
                        title: "Oops...",
                        text: errorMessage,
                        icon: "error"
                    });
                }
            });
        }
    });
});

// btn delete method
$(document).on('click', '.delete-btn', function (e) {
    e.preventDefault();
    
    // Ambil URL dari href
    let url = $(this).attr('href');

    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX request to delete
            $.ajax({
                url: url, // Menggunakan URL dari href tombol delete
                type: 'GET',
                success: function (response) {
                    // Tampilkan pesan berhasil
                    toastr.success(response.success, {timeOut: 2000})
                    
                    // Refresh DataTable atau hapus baris secara manual
                    $('#methodDeposit-table, #paymentDeposit-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred';
                toastr.error(errorMessage, {timeOut: 2000})
            }
            });
        }
    });
});