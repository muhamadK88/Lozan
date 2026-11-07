<script>
    (function () {
        try {
            var stored = localStorage.getItem('lozan-theme');
            var dark = stored ? stored === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.classList.toggle('dark', dark);
            document.documentElement.style.colorScheme = dark ? 'dark' : 'light';
        } catch (error) {
            // If storage is unavailable, fall back to the light theme.
        }
    })();
</script>
