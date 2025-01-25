$(document).ready(function() {
    $('#orders-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: 'order/data',
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
                searchable: true,
            },
            { 
                data: 'product', 
                name: 'product', 
                orderable: true, 
                searchable: true 
            },
            { 
                data: 'id_ref', 
                name: 'id_ref', 
                orderable: true, 
                searchable: true 
            },
            {
                data: 'data',
                name: 'data', 
                orderable: true, 
                searchable: true 
            },
            {
                data: 'price_transaction',
                name: 'price_transaction', 
                orderable: true, 
                searchable: true 
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ]
    });

    // Event untuk tombol edit
    $('#orders-table').on('click', '#detailTrx', function() {
        var id = $(this).data('id');

        // AJAX untuk mengambil data dari backend
        $.ajax({
            url: '/admin/order/get-detail-trx/' + id, // Menggunakan route dengan parameter id
            type: 'GET',
            success: function(response) {
                // Memasukkan data yang diterima dari response ke dalam form modal
                $('#detailTrxModal #date').val(response.trx.updated_at);
                $('#detailTrxModal #product').val(response.trx.name);
                $('#detailTrxModal #target').val(response.trx.data);
                $('#detailTrxModal #sn').val(response.trx.sn.split('/').slice(0,2).join('/'));
                $('#detailTrxModal #note').val(response.trx.note);
            },
            error: function(xhr) {
                toastr.error("Error: " + xhr.responseText, {timeOut: 2000})
            }
        });
    });
});
