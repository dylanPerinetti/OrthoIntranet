import { ref, watch } from 'vue';

const theme = ref(localStorage.getItem('ortho-theme') || 'light');

function applyTheme(value) {
  document.documentElement.classList.toggle('dark', value === 'dark');
  localStorage.setItem('ortho-theme', value);
}

// Initialize on first import
applyTheme(theme.value);

export function useTheme() {
  watch(theme, applyTheme);

  function toggle() {
    theme.value = theme.value === 'dark' ? 'light' : 'dark';
  }

  function set(value) {
    theme.value = value;
  }

  return { theme, toggle, set };
}
