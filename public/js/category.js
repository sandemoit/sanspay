$(document).ready(function() {
    // Inisialisasi DataTable
    $('#category-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/admin/pulsa-ppob/get-category',
        columns: [
            { data: 'brand', name: 'brand', orderable: true, searchable: true  },
            { data: 'name', name: 'name', orderable: true, searchable: true  },
            { data: 'type_category', name: 'type_category', orderable: true, searchable: true  },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

     // Event untuk tombol edit
    $('#category-table').on('click', '.edit-btn', function() {
        var id = $(this).data('id'); // Mengambil ID dari tombol edit yang di-encode dengan base64

        // AJAX untuk mengambil data dari backend
        $.ajax({
            url: '/admin/pulsa-ppob/get-category/' + id, // Menggunakan route dengan parameter id
            type: 'GET',
            success: function(response) {
                // Memasukkan data yang diterima dari response ke dalam form modal
                $('#editModal #name').val(response.category.name);
                $('#editModal #brand').val(response.category.brand);
                $('#editModal #type').val(response.category.type);
                $('#editModal #real').val(response.category.real);
                $('#editModal #jenis').val(response.category.order);
                $('#editModal').data('id', id);
            },
            error: function(xhr) {
                toastr.error("Error: " + xhr.responseText, {timeOut: 2000})
            }
        });
    });

    // Event untuk submit form update ketika tombol Save changes diklik
    $('#editModal .btn-primary').click(function() {
        var id = $('#editModal').data('id'); // Ambil ID yang disimpan di modal
        var name = $('#editModal #name').val();

        // AJAX untuk mengirim data update ke backend
        $.ajax({
            url: '/admin/pulsa-ppob/update-category/' + id,
            type: 'PUT',
            data: {
                name: name,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                // Tutup modal setelah berhasil
                $('#editModal').modal('hide');
                
                // Refresh DataTable untuk menampilkan data terbaru
                $('#category-table').DataTable().ajax.reload();

                // Tampilkan notifikasi atau pesan sukses (opsional)
                toastr.error(response.success, {timeOut: 2000})
            },
            error: function(xhr) {
                toastr.error("Something went wrong!", {timeOut: 2000})
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
                    toastr.error(response.success, {timeOut: 2000})
                    
                    // Refresh DataTable atau hapus baris secara manual
                    $('#category-table').DataTable().ajax.reload();
                },
                error: function (xhr, status, error) {
                    toastr.error("Failed to delete the item.", {timeOut: 2000})
                }
            });
        }
    });
});
