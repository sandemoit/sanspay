$(document).ready(function() {
    $('#products-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/admin/pulsa-ppob/product/get-product',
        columns: [
            {
                data: 'id',
                name: 'id',
                orderable: true, 
            },
            {
                data: 'code',
                name: 'code',
                orderable: true, 
            },
            {
                data: 'product_name',
                name: 'product_name',
                orderable: true, 
            },
            {
                data: 'product_price',
                name: 'product_price',
                orderable: true, 
            },
            {
                data: 'provider',
                name: 'provider',
                orderable: true, 
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ]
    });

    $(document).ready(function() {
        // Gunakan event delegation pada document untuk menangani perubahan pada select type
        $(document).on('change', '.form-select[name="type"]', function() {
            var type = $(this).val();

            // Kosongkan opsi brand dan tambahkan opsi default
            var $brandSelect = $(this).closest('form').find('.form-select[name="brand"]');
            $brandSelect.empty().append('<option selected disabled>Please wait...</option>');

            // Lakukan request AJAX
            $.ajax({
                url: '/admin/pulsa-ppob/product/get-brands', // URL route di Laravel
                type: 'GET',
                data: { type: type },
                success: function(response) {
                    // Hapus teks "Please wait..." lalu tambahkan opsi default
                    $brandSelect.empty().append('<option selected disabled>Select Brand</option>');

                    // Looping array data brands dari response
                    $.each(response, function(index, brand) {
                        $brandSelect.append('<option value="' + brand.name + '">' + brand.name + '</option>');
                    });
                },
                error: function(xhr) {
                    console.error('Error fetching brands:', xhr);
                    // Tampilkan pesan error jika terjadi kesalahan
                    $brandSelect.empty().append('<option selected disabled>Error loading brands</option>');
                }
            });
        });
    });


    $('#addModal .btn-primary').click(function() {
        // Ambil semua data dari form
        var formData = {
            provider: $('#addProductForm #provider').val(),
            code: $('#addProductForm #code').val(),
            type: $('#addProductForm #type').val(),
            brand: $('#addProductForm #brand').val(),
            name: $('#addProductForm #name').val(),
            note: $('#addProductForm #note').val(),
            price: $('#addProductForm #price').val(),
            status: $('#addProductForm #status').val()
        };

        // AJAX untuk mengirim data ke backend
        $.ajax({
            url: '/admin/pulsa-ppob/product/add-product',
            type: 'POST',
            data: formData,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                // Tutup modal setelah berhasil
                $('#addModal').modal('hide');
                
                // Refresh DataTable
                $('#products-table').DataTable().ajax.reload();

                // Tampilkan notifikasi sukses
                toastr.success(response.success, {timeOut: 2000})
            },
            error: function(xhr) {
                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred';
                toastr.error(errorMessage, {timeOut: 2000})
            }
        });
    });

    $('#products-table').on('click', '.edit-btn', function() {
        var id = $(this).data('id'); // Mengambil ID dari tombol edit

        // AJAX untuk mengambil data dari backend
        $.ajax({
            url: '/admin/pulsa-ppob/product/get-product/' + id, // Route ke method backend
            type: 'GET',
            success: function(response) {
                // Isi setiap field pada form modal dengan data dari response
                $('#editModal').data('id', id); // Set ID produk di modal untuk referensi

                // Isi nilai form dengan data yang diterima
                $('#editProductForm #provider').val(response.provider).change();
                $('#editProductForm #code').val(response.code);
                $('#editProductForm #type').val(response.type).change();
                $('#editProductForm #brand').val(response.brand).change();
                $('#editProductForm #name').val(response.name);
                $('#editProductForm #note').val(response.note);
                $('#editProductForm #price').val(response.price);
                $('#editProductForm #status').val(response.status).change();

                // Tampilkan modal setelah data berhasil di-load
                $('#editModal').modal('show');
            },
            error: function(xhr) {
                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred';
                toastr.error(errorMessage, {timeOut: 2000})
            }
        });
    });

    // Event untuk submit form edit ketika tombol Save changes diklik
    $('#editModal .btn-primary').click(function() {
        // Ambil ID produk yang diedit dari atribut data (misalnya data-id) pada tombol
        var productId = $('#editModal').data('id');

        // Ambil semua data dari form edit
        var formData = {
            provider: $('#editProductForm #provider').val(),
            code: $('#editProductForm #code').val(),
            type: $('#editProductForm #type').val(),
            brand: $('#editProductForm #brand').val(),
            name: $('#editProductForm #name').val(),
            note: $('#editProductForm #note').val(),
            price: $('#editProductForm #price').val(),
            status: $('#editProductForm #status').val()
        };

        // AJAX untuk mengirim data edit ke backend
        $.ajax({
            url: '/admin/pulsa-ppob/product/update-product/' + productId, // URL endpoint edit product dengan ID
            type: 'PUT',
            data: formData,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                // Tutup modal setelah berhasil
                $('#editModal').modal('hide');
                
                // Refresh DataTable untuk menampilkan data terbaru
                $('#products-table').DataTable().ajax.reload();

                // Tampilkan pesan berhasil
                toastr.success(response.success, {timeOut: 2000})
                },
                error: function(xhr) {
                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred';
                toastr.error(errorMessage, {timeOut: 2000})
            }
        });
    });
});

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
                    $('#products-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred';
                toastr.error(errorMessage, {timeOut: 2000})
            }
            });
        }
    });
});

$(document).on('click', '#pullProductBtn', function (e) {
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
        confirmButtonText: "Yes, pull product!"
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX request to delete
            $.ajax({
                url: url, // Menggunakan URL dari href tombol delete
                type: 'GET',
                success: function (response) {
                    // Tampilkan pesan berhasil
                    toastr.success("Product has been pulled.", {timeOut: 2000})

                    // Refresh DataTable atau hapus baris secara manual
                    $('#products-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred';
                toastr.error(errorMessage, {timeOut: 2000})
            }
            });
        }
    });
});

$(document).on('click', '#delProductBtn', function (e) {
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
        confirmButtonText: "Yes, delete product!"
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
                    $('#products-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred';
                toastr.error(errorMessage, {timeOut: 2000})
            }
            });
        }
    });
});
