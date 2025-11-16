

const THEME_KEY = 'blog-theme';

export function initTheme() {
    
    const savedTheme = localStorage.getItem(THEME_KEY);
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const theme = savedTheme || (prefersDark ? 'dark' : 'light');
    
    applyTheme(theme);
    
    
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (!localStorage.getItem(THEME_KEY)) {
            applyTheme(e.matches ? 'dark' : 'light');
        }
    });
}

export function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    applyTheme(newTheme);
    localStorage.setItem(THEME_KEY, newTheme);
}

function applyTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    
    
    document.documentElement.style.transition = 'background-color 0.3s ease, color 0.3s ease';
    
    
    window.dispatchEvent(new CustomEvent('themechange', { detail: { theme } }));
}

export function getCurrentTheme() {
    return document.documentElement.getAttribute('data-theme') || 'light';
}
