document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.querySelector('.navbar');
    function navbarScroll() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    }
    navbarScroll();
    window.addEventListener('scroll', navbarScroll);
});