(() => {
    const button = document.querySelector('.mobile-menu-toggle');
    const navigation = document.querySelector('.main-navigation');

    if (!button || !navigation) {
        return;
    }

    button.addEventListener('click', () => {
        const isOpen = navigation.classList.toggle('is-open');
        button.setAttribute('aria-expanded', String(isOpen));
    });
})();
