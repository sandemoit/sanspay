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
                data: 'target',
                name: 'target', 
                orderable: true, 
                searchable: true 
            },
            {
                data: 'total_payment',
                name: 'total_payment', 
                orderable: true, 
                searchable: true 
            },
            {
                data: 'status',
                name: 'status',
                orderable: false,
                searchable: false
            }
        ]
    });
});
