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
            { data: 'trx_refund', name: 'trx_refund', orderable: true, searchable: true  },
            { data: 'name', name: 'name', orderable: true, searchable: true  },
            { data: 'data', name: 'data', orderable: true, searchable: true  },
            { data: 'sn', name: 'sn', orderable: true, searchable: true  },
            { data: 'trx_price', name: 'trx_price', orderable: true, searchable: true  },
            { data: 'note', name: 'note', orderable: true, searchable: true  },
            { data: 'status', name: 'status', orderable: true, searchable: true  },
        ]
    });
});