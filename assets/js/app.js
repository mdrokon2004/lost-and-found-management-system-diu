(function () {
  const root = document.documentElement;
  const storageKey = 'diu-theme';
  const dismissDelay = 4500;

  function preferredTheme() {
    const saved = localStorage.getItem(storageKey);
    if (saved === 'dark' || saved === 'light') return saved;
    return 'light';
  }

  function applyTheme(theme) {
    const safeTheme = theme === 'dark' ? 'dark' : 'light';
    root.setAttribute('data-theme', safeTheme);
    document.body?.setAttribute('data-theme', safeTheme);
    localStorage.setItem(storageKey, safeTheme);
    updateThemeControls(safeTheme);
  }

  function updateThemeControls(theme) {
    document.querySelectorAll('[data-theme-choice]').forEach(button => {
      const active = button.dataset.themeChoice === theme;
      button.classList.toggle('active', active);
      button.setAttribute('aria-pressed', active ? 'true' : 'false');
    });
  }

  // Apply immediately so the page never needs a reload for theme switching.
  try {
    applyTheme(preferredTheme());
  } catch (e) {
    root.setAttribute('data-theme', 'light');
  }

  document.addEventListener('DOMContentLoaded', () => {
    updateThemeControls(root.dataset.theme || 'light');

    document.querySelectorAll('[data-theme-choice]').forEach(button => {
      button.addEventListener('click', () => applyTheme(button.dataset.themeChoice));
    });

    document.querySelectorAll('[data-confirm]').forEach(el => {
      el.addEventListener('click', e => {
        if (!confirm(el.dataset.confirm || 'Are you sure?')) e.preventDefault();
      });
    });

    document.querySelectorAll('.auto-dismiss').forEach(el => {
      setTimeout(() => el.remove(), dismissDelay);
    });
  });
})();