// Konstanta untuk tema
const THEMES = {
    LIGHT: 'light-theme',
    DARK: 'dark-theme'
};

// Fungsi untuk menyimpan dan mengatur tema
function setTheme(theme) {
    try {
        // Validasi tema yang valid
        if (!Object.values(THEMES).includes(theme)) {
            throw new Error('Tema tidak valid');
        }

        // Simpan ke localStorage
        localStorage.setItem('theme', theme);

        // Update class di element html
        const htmlElement = document.documentElement;
        htmlElement.classList.remove(...Object.values(THEMES));
        htmlElement.classList.add(theme);

        // Update ikon tema
        updateThemeIcon(theme);

    } catch (error) {
        console.error('Gagal mengatur tema:', error);
    }
}

// Fungsi untuk memperbarui ikon tema
function updateThemeIcon(theme) {
    const themeIcon = document.querySelector('#theme-toggle ion-icon');
    if (themeIcon) {
        themeIcon.setAttribute('name', theme === THEMES.DARK ? 'sunny-outline' : 'moon-outline');
    }
}

// Inisialisasi tema saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    // Ambil tema dari localStorage atau gunakan default light
    const savedTheme = localStorage.getItem('theme') || THEMES.LIGHT;
    setTheme(savedTheme);
});

// Event listener untuk toggle tema
document.getElementById('theme-toggle')?.addEventListener('click', () => {
    const currentTheme = localStorage.getItem('theme');
    const newTheme = currentTheme === THEMES.DARK ? THEMES.LIGHT : THEMES.DARK;
    setTheme(newTheme);
});