$(document).ready(function() {
    // Inisialisasi DataTable
    $('#history-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/order/get-history',
        columns: [{
                data: null,
                name: 'no',
                orderable: true,
                searchable: false,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'trx_id', name: 'trx_id', orderable: true, searchable: true  },
            { data: 'status', name: 'status', orderable: true, searchable: true  },
            { data: 'name', name: 'name', orderable: true, searchable: true  },
            { data: 'trx_price', name: 'trx_price', orderable: true, searchable: true  },
            { data: 'data', name: 'data', orderable: true, searchable: true  },
            { data: 'sn', name: 'sn', orderable: true, searchable: true  },
            { data: 'note', name: 'note', orderable: true, searchable: true  },
            { data: 'trx_refund', name: 'trx_refund', orderable: true, searchable: true  },
        ]
    });

    // Event untuk tombol edit
    $('#history-table').on('click', '#detailTrx', function() {
        var id = $(this).data('id');

        // AJAX untuk mengambil data dari backend
        $.ajax({
            url: '/order/get-detail-trx/' + id, // Menggunakan route dengan parameter id
            type: 'GET',
            success: function(response) {
                // Memasukkan data yang diterima dari response ke dalam form modal
                $('#detailTrxModal #order_id').text(response.order_id);
                $('#detailTrxModal #code').text(response.code);
                $('#detailTrxModal #pengirim').text(response.pengirim);
                $('#detailTrxModal #name').text(response.name);
                $('#detailTrxModal #data').text(response.data);
                $('#detailTrxModal #status').text(response.status);
                $('#detailTrxModal #note').text(response.note);
                $('#detailTrxModal #price').text('Rp'+response.price);
                $('#detailTrxModal #sn').text(response.sn || '-');
                $('#detailTrxModal #created_at').text(response.created_at);
                $('#detailTrxModal #updated_at').text(response.updated_at);
            },
            error: function(xhr) {
                toastr.error("Error: " + xhr.responseText, {timeOut: 2000})
            }
        });
    });
});