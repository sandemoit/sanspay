$(document).ready(function() {
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
                    $methodSelect.append('<option value="' + method.code + '">' + method.name + '</option>');
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
                    $('#code_unique').val(response.code_unique);
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