// Fungsi untuk menyimpan theme ke Local Storage dan mengatur class di <html>
function setTheme(theme) {
    localStorage.setItem('theme', theme); // Simpan theme ke Local Storage
    document.documentElement.classList.remove('light-theme', 'dark-theme'); // Hapus class sebelumnya
    document.documentElement.classList.add(theme); // Tambahkan class baru (light-theme atau dark-theme)
}

// Saat halaman selesai dimuat
document.addEventListener('DOMContentLoaded', function () {
    const savedTheme = localStorage.getItem('theme') || 'light-theme'; // Default ke light-theme
    setTheme(savedTheme); // Terapkan theme yang disimpan
});

// Fungsi toggle theme ketika tombol diklik
document.getElementById('theme-toggle').addEventListener('click', function () {
    const currentTheme = localStorage.getItem('theme') === 'dark-theme' ? 'light-theme' : 'dark-theme';
    setTheme(currentTheme); // Ganti theme antara 'light-theme' dan 'dark-theme'
});