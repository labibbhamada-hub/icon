<footer class="footer">
    <div class="container">
        <div class="row gy-5">
            {{-- Brand --}}
            <div class="col-lg-4">
                <a href="{{ url('/') }}" class="footer-brand">
                    <img src="{{ asset('assets/images/logo/logo-bhamada.png') }}" alt="Universitas Bhamada" class="footer-logo">
                    <div>
                        <h5>Universitas Bhamada</h5>
                        <span>International Conference</span>
                    </div>
                </a>

                <p class="footer-description">
                    ICON 2026 is an international conference that brings together
                    researchers, academics, practitioners, and students to share
                    ideas and innovations in health, science, and technology.
                </p>

                <div class="footer-social">
                    <a href="#" aria-label="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" aria-label="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" aria-label="LinkedIn">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    <a href="#" aria-label="YouTube">
                        <i class="bi bi-youtube"></i>
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="col-6 col-lg-2">
                <h6 class="footer-title">Quick Links</h6>
                <ul class="footer-links">
                    <li>
                        <a href="#home">Home</a>
                    </li>
                    <li>
                        <a href="#about">About</a>
                    </li>
                    <li>
                        <a href="#topics">Topics</a>
                    </li>
                    <li>
                        <a href="#speakers">Speakers</a>
                    </li>
                </ul>
            </div>

            {{-- Conference --}}
            <div class="col-6 col-lg-3">
                <h6 class="footer-title">Conference</h6>
                <ul class="footer-links">
                    <li>
                        <a href="#important-dates">Important Dates</a>
                    </li>
                    <li>
                        <a href="#call-for-papers">Call for Papers</a>
                    </li>
                    <li>
                        <a href="#partners">Our Partners</a>
                    </li>
                    <li>
                        <a href="#contact">Contact</a>
                    </li>
                </ul>
            </div>

            {{-- Contact --}}
            <div class="col-lg-3">
                <h6 class="footer-title">Get In Touch</h6>
                <ul class="footer-contact">
                    <li>
                        <i class="bi bi-geo-alt"></i>
                        <span>Tegal, Central Java, Indonesia</span>
                    </li>
                    <li>
                        <i class="bi bi-envelope"></i>
                        <span>icon@bhamada.ac.id</span>
                    </li>
                    <li>
                        <i class="bi bi-calendar-event"></i>
                        <span>15–16 July 2026</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom --}}
        <div class="footer-bottom">
            <p>
                © {{ date('Y') }} Universitas Bhamada.
                All rights reserved.
            </p>
            <div class="footer-bottom-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms & Conditions</a>
            </div>
        </div>
    </div>
</footer>
