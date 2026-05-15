document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('click', (event) => {
        const element = event.target.closest('[data-confirm]');

        if (! element) {
            return;
        }

        if (! window.confirm(element.dataset.confirm)) {
            event.preventDefault();
            event.stopPropagation();
        }
    });

    document.querySelectorAll('.fade-up').forEach((element, index) => {
        element.style.animationDelay = `${index * 70}ms`;
    });
});
