</div> <!-- End of content area -->
</div> <!-- End of flex layout -->

<script>
    // Basic mobile sidebar toggle
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.querySelector('aside');
        const toggle = document.querySelector('button.md\\:hidden');

        if (toggle) {
            toggle.addEventListener('click', () => {
                sidebar.classList.toggle('hidden');
                sidebar.classList.toggle('block');
            });
        }
    });
</script>
</body>

</html>
