document.addEventListener('DOMContentLoaded', function () {
    const navbar = document.querySelector('#site-nav');
    const navLinks = document.querySelectorAll('#site-nav .nav-link');

    function updateNavbar() {
        if (!navbar) {
            return;
        }

        navbar.classList.toggle('navbar-solid', window.scrollY > 30);
    }

    function updateActiveLink() {
        const sections = document.querySelectorAll('section[id], header[id]');
        const offset = (navbar ? navbar.offsetHeight : 0) + 20;
        let current = 'about';

        sections.forEach(function (section) {
            if (window.scrollY + offset >= section.offsetTop) {
                current = section.id;
            }
        });

        navLinks.forEach(function (link) {
            link.classList.toggle('active', link.getAttribute('href') === '#' + current);
        });
    }

    updateNavbar();
    updateActiveLink();

    window.addEventListener('scroll', function () {
        updateNavbar();
        updateActiveLink();
    }, { passive: true });

    navLinks.forEach(function (link) {
        link.addEventListener('click', function () {
            const target = this.getAttribute('href');
            if (target && target.charAt(0) === '#') {
                const element = document.querySelector(target);
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }

            const collapse = document.querySelector('#navbar-items');
            if (collapse && window.bootstrap && window.innerWidth < 992) {
                const instance = window.bootstrap.Collapse.getInstance(collapse);
                if (instance) {
                    instance.hide();
                }
            }
        });
    });
});
