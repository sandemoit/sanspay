$(document).ready(function() {
    // Inisialisasi DataTable
    $('#ticket-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/admin/ticket/get',
        columns: [
            {
                data: null,
                name: 'no',
                orderable: true,
                searchable: false,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'date', name: 'date', orderable: true, searchable: true },
            { data: 'username', name: 'username', orderable: true, searchable: true },
            { data: 'subject', name: 'subject', orderable: true, searchable: true },
            { data: 'status', name: 'status', orderable: true, searchable: true },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
