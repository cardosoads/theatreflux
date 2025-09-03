import { ref, computed, watch, onMounted, readonly } from 'vue';

/**
 * Composable for theme management (light/dark mode)
 * Provides reactive theme state with localStorage persistence
 * Automatically applies theme classes to document element
 */
export function useTheme() {
  // Theme state - defaults to 'light'
  const theme = ref('light');
  
  // Storage key for localStorage
  const THEME_STORAGE_KEY = 'shadcn-theme';
  
  // Computed property to check if current theme is dark
  const isDark = computed(() => theme.value === 'dark');
  
  // Computed property to check if current theme is light
  const isLight = computed(() => theme.value === 'light');
  
  /**
   * Set theme and persist to localStorage
   * @param {string} newTheme - 'light' or 'dark'
   */
  const setTheme = (newTheme) => {
    if (newTheme !== 'light' && newTheme !== 'dark') {
      console.warn(`Invalid theme: ${newTheme}. Using 'light' as fallback.`);
      newTheme = 'light';
    }
    
    theme.value = newTheme;
    localStorage.setItem(THEME_STORAGE_KEY, newTheme);
    applyThemeToDocument(newTheme);
  };
  
  /**
   * Toggle between light and dark themes
   */
  const toggleTheme = () => {
    const newTheme = theme.value === 'light' ? 'dark' : 'light';
    setTheme(newTheme);
  };
  
  /**
   * Apply theme class to document element
   * @param {string} themeValue - Theme to apply
   */
  const applyThemeToDocument = (themeValue) => {
    const root = document.documentElement;
    
    // Tailwind CSS darkMode: ['class'] expects only 'dark' class
    // Light mode = no class, Dark mode = 'dark' class
    if (themeValue === 'dark') {
      root.classList.add('dark');
    } else {
      root.classList.remove('dark');
    }
  };
  
  /**
   * Load theme from localStorage or detect system preference
   */
  const initializeTheme = () => {
    // Try to get theme from localStorage first
    const savedTheme = localStorage.getItem(THEME_STORAGE_KEY);
    
    if (savedTheme && (savedTheme === 'light' || savedTheme === 'dark')) {
      setTheme(savedTheme);
      return;
    }
    
    // Fallback to system preference
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    setTheme(prefersDark ? 'dark' : 'light');
  };
  
  /**
   * Listen for system theme changes
   */
  const setupSystemThemeListener = () => {
    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    
    const handleSystemThemeChange = (e) => {
      // Only update if no theme is saved in localStorage
      const savedTheme = localStorage.getItem(THEME_STORAGE_KEY);
      if (!savedTheme) {
        setTheme(e.matches ? 'dark' : 'light');
      }
    };
    
    mediaQuery.addEventListener('change', handleSystemThemeChange);
    
    // Return cleanup function
    return () => {
      mediaQuery.removeEventListener('change', handleSystemThemeChange);
    };
  };
  
  // Watch for theme changes and apply to document
  watch(theme, (newTheme) => {
    applyThemeToDocument(newTheme);
  }, { immediate: true });
  
  // Initialize theme on component mount
  onMounted(() => {
    initializeTheme();
    setupSystemThemeListener();
  });
  
  return {
    // State
    theme: readonly(theme),
    isDark,
    isLight,
    
    // Actions
    setTheme,
    toggleTheme,
    
    // Utilities
    initializeTheme
  };
}

// Export default for convenience
export default useTheme;