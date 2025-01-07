$(document).ready(function() {
    // Inisialisasi DataTable
    $('#referrals-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/referral/get',
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
                searchable: true,
            },
            {
                data: 'username_from',
                name: 'username_from',
                orderable: true,
                searchable: true,
            },
            {
                data: 'username_to',
                name: 'username_to',
                orderable: true,
                searchable: true,
            },
            {
                data: 'point',
                name: 'point',
                orderable: true,
                searchable: true,
            },
            {
                data: 'status',
                name: 'status',
                orderable: true,
                searchable: true,
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
            },
        ]
    });
});