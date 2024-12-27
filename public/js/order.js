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
});
