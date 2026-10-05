document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.querySelector('.site-nav');
    const navbarMenu = document.getElementById('navbar-items');
    const navLinks = document.querySelectorAll(
        '.site-nav .nav-link[href^="#"]'
    );


    /*
     * =========================================================
     * NAVBAR SCROLL
     * =========================================================
     */

    if (navbar) {
        const navbarScroll = () => {
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-solid');
            } else {
                navbar.classList.remove('navbar-solid');
            }
        };

        navbarScroll();

        window.addEventListener('scroll', navbarScroll);
    }


    /*
     * =========================================================
     * NAVBAR COLLAPSE
     * =========================================================
     */

    let navbarCollapse = null;

    if (
        navbarMenu &&
        typeof bootstrap !== 'undefined' &&
        typeof bootstrap.Collapse !== 'undefined'
    ) {
        navbarCollapse = bootstrap.Collapse.getOrCreateInstance(
            navbarMenu,
            {
                toggle: false
            }
        );
    }


    /*
     * =========================================================
     * NAVIGATION LINKS
     * =========================================================
     */

    navLinks.forEach((link) => {
        link.addEventListener('click', () => {

            /*
             * Update active navigation
             */
            navLinks.forEach((navLink) => {
                navLink.classList.remove('active');
            });

            link.classList.add('active');


            /*
             * Close mobile navigation
             */
            if (
                navbarCollapse &&
                window.innerWidth < 992
            ) {
                navbarCollapse.hide();
            }
        });
    });
});