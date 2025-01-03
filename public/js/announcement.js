$(document).ready(function() {
    $('#announcement-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: 'announcement/get',
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
                data: 'title',
                orderable: true,
            },
            {
                data: 'image',
                render: function(data) {
                    return `<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" data-src="/${data}" width="100" alt="Image" class="lazyload">`;
                },
                orderable: true,
            },
            {
                data: 'slug',
                orderable: true,
            },
            {
                data: 'type',
                orderable: true,
            },
            {
                data: 'viewer',
                orderable: true,
            },
            {
                data: 'action',
                name: 'action',
                orderable: true,
            },
        ]
    });

    // Lazy loading gambar
    table.on('draw.dt', function() {
        var images = table.$('img.lazyload');
        images.each(function() {
            var img = $(this);
            if (!img.attr('src').includes('data:image/png;base64')) {
                return;
            }
            img.attr('src', img.attr('data-src'));
            img.removeClass('lazyload');
        });
    });
    
    tinymce.init({
        selector: '#description',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });
    $(document).on('change', '.form-select[name="type"]', function () {
        var type = $(this).val();

        // Kosongkan opsi image dan tambahkan opsi default
        var $imageInput = $(this).closest('form').find('.form-control[name="image"]');
        if (type == 'banner') {
            $imageInput.parent().removeClass('d-none');
            $imageInput.attr('required', true);
        } else {
            $imageInput.parent().addClass('d-none');
            $imageInput.removeAttr('required');
        }
    })
})

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
                    $('#announcement-table').DataTable().ajax.reload();
                },
                error: function (xhr, status, error) {
                    toastr.error("Failed to delete the item.", {timeOut: 2000})
                }
            });
        }
    });
});
