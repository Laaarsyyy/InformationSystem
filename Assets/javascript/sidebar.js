document.addEventListener("DOMContentLoaded", function () {
    const toggleButton = document.getElementById('toggle-btn');
    const sidebar = document.getElementById('sidebar');

    if (toggleButton && sidebar) {
        toggleButton.addEventListener('click', function () {
            sidebar.classList.toggle('close');
            toggleButton.classList.toggle('rotate');
        });
    }
});