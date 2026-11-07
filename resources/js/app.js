const root = document.documentElement;
const storageKey = 'lozan-theme';

function applyTheme(theme) {
    const isDark = theme === 'dark';

    root.classList.toggle('dark', isDark);
    root.style.colorScheme = isDark ? 'dark' : 'light';

    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.setAttribute(
            'aria-label',
            isDark ? 'گۆڕین بۆ ڕووکاری ڕۆشن' : 'گۆڕین بۆ ڕووکاری تاریک',
        );
    });
}

function initialTheme() {
    const stored = localStorage.getItem(storageKey);

    if (stored === 'dark' || stored === 'light') {
        return stored;
    }

    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

applyTheme(initialTheme());

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (event) => {
    if (!localStorage.getItem(storageKey)) {
        applyTheme(event.matches ? 'dark' : 'light');
    }
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const next = root.classList.contains('dark') ? 'light' : 'dark';

            localStorage.setItem(storageKey, next);
            applyTheme(next);
        });
    });

    const menuToggle = document.querySelector('[data-mobile-menu-toggle]');
    const menu = document.querySelector('[data-mobile-menu]');

    if (menuToggle && menu) {
        menuToggle.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            menuToggle.setAttribute(
                'aria-expanded',
                menu.classList.contains('hidden') ? 'false' : 'true',
            );
        });
    }
});
