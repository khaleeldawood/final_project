/**
 * ThemeService - Handles Dark/Light mode toggling and persistence
 */
const ThemeService = {
    // Config
    storageKey: 'theme',
    defaultTheme: 'light',
    toggleSelector: '[data-theme-toggle]',
    attribute: 'data-theme',

    init() {
        // 1. Load saved theme or default
        const storedTheme = localStorage.getItem(this.storageKey) || this.defaultTheme;
        this.setTheme(storedTheme);

        // 2. Bind event listeners
        const toggleBtn = document.querySelector(this.toggleSelector);
        if (toggleBtn) {
            this.updateIcon(storedTheme);
            toggleBtn.addEventListener('click', () => this.toggle());
        }
    },

    toggle() {
        const currentTheme = document.documentElement.getAttribute(this.attribute);
        const nextTheme = currentTheme === 'light' ? 'dark' : 'light';

        this.setTheme(nextTheme);
        this.updateIcon(nextTheme);
    },

    setTheme(theme) {
        document.documentElement.setAttribute(this.attribute, theme);
        localStorage.setItem(this.storageKey, theme);
    },

    updateIcon(theme) {
        const toggleBtn = document.querySelector(this.toggleSelector);
        if (toggleBtn) {
            toggleBtn.innerHTML = theme === 'light'
                ? '<i class="bi bi-moon-fill"></i>'
                : '<i class="bi bi-sun-fill"></i>';
        }
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    ThemeService.init();
});
