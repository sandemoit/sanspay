// table management deposit
$(document).ready(function() {
    $('#historyDeposit-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: 'deposit/get',
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
                data: 'INVDate',
                name: 'INVDate',
                orderable: true,
            },
            {
                data: 'Method',
                name: 'Method',
                orderable: true,
            },
            {
                data: 'TotalTransfer',
                name: 'TotalTransfer',
                orderable: true,
            },
            {
                data: 'Amount',
                name: 'Amount',
                orderable: true,
            },
            {
                data: 'Status',
                name: 'Status',
                orderable: true,
            },
        ]
    });
});

$(document).ready(function() {
    // Simpan minimum value untuk validasi
    let minimumValue = 0;

    // Event change pada methodpayment
    $(document).on('change', '#methodpayment', function() {
        var selectedOption = $(this).find(':selected'); // Opsi yang dipilih
        minimumValue = parseInt(selectedOption.data('minimum')) || 0; // Ambil nilai minimum dari opsi

        // Tampilkan nilai minimum pada teks merah
        $('#minimumValue').text(new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(minimumValue));
    });

    // Event input pada nominalDeposit
    $(document).on('input', '#nominalDeposit', function() {
        var nominal = parseInt($(this).val().replace(/\D/g, '')) || 0; // Konversi ke angka
        if (nominal < minimumValue) {
            $('#nominalWarning').show(); // Tampilkan pesan error
        } else {
            $('#nominalWarning').hide(); // Sembunyikan pesan error
        }
    });
    
    // Gunakan event delegation pada document untuk menangani perubahan pada select type
    $(document).on('change', '.form-select[name="typepayment"]', function() {
        var type = $(this).val();

        // Kosongkan opsi method dan tambahkan opsi default
        var $methodSelect = $(this).closest('form').find('.form-select[name="methodpayment"]');
        $methodSelect.empty().append('<option selected disabled>Please wait...</option>');

        // Lakukan request AJAX
        $.ajax({
            url: '/deposit/new/getMethod', // URL route di Laravel
            type: 'GET',
            data: { type: type },
            success: function(response) {
                // Hapus teks "Please wait..." lalu tambahkan opsi default
                $methodSelect.empty().append('<option selected disabled>Select Method</option>');

                // Looping array data methods dari response
                $.each(response, function(index, method) {
                    $methodSelect.append('<option value="' + method.code + '" data-minimum="' + method.minimum + '">' + method.name + ' (Min. ' + new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(method.minimum) + ')</option>');
                });
            },
            error: function(xhr) {
                console.error('Error fetching methods:', xhr);
                // Tampilkan pesan error jika terjadi kesalahan
                $methodSelect.empty().append('<option selected disabled>Error loading methods</option>');
            }
        });
    });
});

$(document).ready(function() {
    // Ketika nominal deposit berubah
    $('#nominalDeposit').on('input', function() {
        const nominalDeposit = $(this).val();
        const methodPayment = $('#methodpayment').val();

        if (nominalDeposit && methodPayment) {
            // Kirim permintaan AJAX
            $.ajax({
                url: '/deposit/new/calculate-fee', // Ganti dengan route yang sesuai
                type: 'GET', // Ubah ke GET
                data: {
                    nominalDeposit: nominalDeposit,
                    methodpayment: methodPayment,
                },
                success: function(response) {
                    // Menampilkan respons di input yang sesuai
                    $('#fee').val(response.fee);
                    $('#total_transfer').val(response.total_transfer);
                    $('#saldo_recived').val(response.saldo_received);
                },
                error: function(xhr) {
                    // Menangani kesalahan
                    if (xhr.status === 404) {
                        toastr.error('Metode pembayaran tidak ditemukan.', {timeOut:3000});
                    } else {
                        toastr.error('Terjadi kesalahan. Silakan coba lagi.', {timeOut:3000});
                    }
                }
            });
        }
    });

    // Tambahkan event listener untuk method payment jika diperlukan
    $('#methodpayment').change(function() {
        $('#nominalDeposit').trigger('input'); // Trigger ulang input nominal
    });
});

// reset deposit form
$(document).on('click', '#resetDeposit', function (e) {
    // Select the form
    const form = document.querySelector('form');

    // Reset all form fields to their default values
    form.reset();

    // Clear all readonly fields
    $('#typepayment').trigger('reset');
    $('#methodpayment').trigger('reset');
    $('#nominalDeposit').trigger('reset');
    $('#fee').trigger('reset');
    $('#total_transfer').trigger('reset');
    $('#saldo_recived').trigger('reset');
});