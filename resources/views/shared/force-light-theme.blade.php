<script>
    (() => {
        const applyLightTheme = () => {
            document.documentElement.setAttribute('data-bs-theme', 'light');
            document.documentElement.style.colorScheme = 'light';
        };

        try {
            window.localStorage.setItem('lte-theme', 'light');
        } catch (error) {
            // Tema tetap dipaksa terang melalui atribut dokumen.
        }

        applyLightTheme();

        const enforceLightTheme = () => {
            applyLightTheme();

            if (typeof window.matchMedia === 'function') {
                const mediaQuery = window.matchMedia(
                    '(prefers-color-scheme: dark)'
                );

                if (typeof mediaQuery.addEventListener === 'function') {
                    mediaQuery.addEventListener('change', applyLightTheme);
                } else if (typeof mediaQuery.addListener === 'function') {
                    mediaQuery.addListener(applyLightTheme);
                }
            }
        };

        if (document.readyState === 'loading') {
            document.addEventListener(
                'DOMContentLoaded',
                enforceLightTheme, {
                    once: true
                }
            );
        } else {
            enforceLightTheme();
        }
    })();
</script>
