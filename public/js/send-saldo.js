$(document).ready(function() {
    $('#kirim-saldo-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/getDataKirimSaldo',
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
                data: 'reciver',
                name: 'reciver',
                orderable: true, 
            },
            {
                data: 'nominal_transfer',
                name: 'nominal_transfer',
                orderable: true, 
            },
            {
                data: 'date',
                name: 'date',
                orderable: true, 
            },
            {
                data: 'id_uniq',
                name: 'id_uniq',
                orderable: true, 
            },
            {
                data: 'sender',
                name: 'sender',
                orderable: true, 
            },
            {
                data: 'status',
                name: 'status',
                orderable: true, 
            },
        ]
    });
});