(() => {
    const storageKey = 'mega-admin-theme';
    const root = document.documentElement;
    const toggle = document.querySelector('[data-theme-toggle]');

    function storedTheme() {
        try {
            return localStorage.getItem(storageKey);
        } catch {
            return null;
        }
    }

    function setTheme(theme, persist = false) {
        const isDark = theme === 'dark';
        root.dataset.theme = isDark ? 'dark' : 'light';

        if (toggle) {
            const label = toggle.querySelector('[data-theme-label]');
            const icon = toggle.querySelector('svg');
            toggle.setAttribute('aria-pressed', String(isDark));
            toggle.setAttribute('aria-label', isDark ? 'Ativar modo claro' : 'Ativar modo escuro');
            if (label) label.textContent = isDark ? 'Modo claro' : 'Modo escuro';
            if (icon) {
                icon.innerHTML = isDark
                    ? '<path d="M20.9 13A8.5 8.5 0 0 1 11 3.1 8.5 8.5 0 1 0 20.9 13Z"></path>'
                    : '<circle cx="12" cy="12" r="4"></circle><path d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42"></path>';
            }
        }

        if (persist) {
            try {
                localStorage.setItem(storageKey, isDark ? 'dark' : 'light');
            } catch {
                // O tema continua ativo nesta página mesmo se o navegador bloquear armazenamento.
            }
        }
    }

    const preference = storedTheme();
    const initialTheme = preference || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    setTheme(initialTheme);

    if (toggle) {
        toggle.addEventListener('click', () => {
            setTheme(root.dataset.theme === 'dark' ? 'light' : 'dark', true);
        });
    }
})();
