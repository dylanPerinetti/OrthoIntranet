import { ref, watch, readonly } from 'vue';

const theme = ref(localStorage.getItem('ortho-theme') || 'light');

/* Media query for system preference */
const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

/** Resolve the effective dark/light based on stored value */
function isDark(value) {
  if (value === 'system') return mediaQuery.matches;
  return value === 'dark';
}

function applyTheme(value) {
  document.documentElement.classList.toggle('dark', isDark(value));
  localStorage.setItem('ortho-theme', value);
}

/* Listen for OS theme changes — only matters when mode is 'system' */
mediaQuery.addEventListener('change', () => {
  if (theme.value === 'system') {
    document.documentElement.classList.toggle('dark', mediaQuery.matches);
  }
});

// Initialize on first import
applyTheme(theme.value);

export function useTheme() {
  watch(theme, applyTheme);

  function toggle() {
    const order = ['light', 'dark', 'system'];
    const idx = order.indexOf(theme.value);
    theme.value = order[(idx + 1) % order.length];
  }

  function set(value) {
    theme.value = value;
  }

  return { theme, toggle, set };
}
