(() => {
    const button = document.querySelector('.store-menu-toggle');
    const navigation = document.querySelector('.store-navigation');

    if (!button || !navigation) {
        return;
    }

    button.addEventListener('click', () => {
        const isOpen = navigation.classList.toggle('is-open');
        button.setAttribute('aria-expanded', String(isOpen));
    });
})();
