document.addEventListener('DOMContentLoaded', function() {
    // Fungsi untuk mengatur visibility input
    function setupVisibility(inputId, buttonId) {
        const input = document.getElementById(inputId);
        const button = document.getElementById(buttonId);
        
        if (input && button) {
            button.addEventListener('click', function() {
                if (input.type === 'password') {
                    input.type = 'text';
                    button.textContent = 'Tutup';
                } else {
                    input.type = 'password';
                    button.textContent = 'Lihat';
                }
            });
        }
    }

    // Setup untuk PIN
    setupVisibility('pin', 'see-pin');
    
    // Setup untuk Password
    setupVisibility('password', 'see-password');
    
    // Setup untuk Konfirmasi Password
    setupVisibility('password_confirmation', 'see-password-confirmation');
}); 