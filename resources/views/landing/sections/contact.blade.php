<section id="contact" class="contact-section">
    <div class="container">
        {{-- Section Header --}}
        <div class="section-heading text-center">
            <span class="section-badge">
                <span class="badge-dot"></span>
                Get In Touch
            </span>
            <h2>
                Let's <span>Connect</span>
                With Us
            </h2>
            <p>
                Have questions about ICON 2026?
                Our team is ready to help you with information
                about registration, submission, and the conference.
            </p>
        </div>

        <div class="row g-4 align-items-stretch">
            {{-- Contact Information --}}
            <div class="col-lg-5">
                <div class="contact-info-card">
                    <div class="contact-info-header">
                        <span class="contact-icon">
                            <i class="bi bi-chat-dots"></i>
                        </span>
                        <div>
                            <h4>Conference Secretariat</h4>
                            <p>
                                Get in touch with our conference team.
                            </p>
                        </div>
                    </div>

                    <div class="contact-info-list">
                        {{-- Location --}}
                        <div class="contact-info-item">
                            <div class="contact-item-icon">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <div>
                                <span>Location</span>
                                <strong>
                                    Tegal, Central Java, Indonesia
                                </strong>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="contact-info-item">
                            <div class="contact-item-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <div>
                                <span>Email</span>
                                <strong>
                                    icon@bhamada.ac.id
                                </strong>
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div class="contact-info-item">
                            <div class="contact-item-icon">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div>
                                <span>Phone</span>
                                <strong>
                                    +62 8xx-xxxx-xxxx
                                </strong>
                            </div>
                        </div>

                        {{-- Conference Date --}}
                        <div class="contact-info-item">
                            <div class="contact-item-icon">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <div>
                                <span>Conference Date</span>
                                <strong>
                                    15–16 July 2026
                                </strong>
                            </div>
                        </div>
                    </div>

                    {{-- Social Media --}}
                    <div class="contact-social">
                        <span>Follow ICON 2026</span>
                        <div class="social-links">
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

                    <div class="contact-response-time">
                        <div class="response-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div class="response-content">
                            <span>Response Time</span>
                            <strong>Within 24 Hours</strong>
                            <small>
                                Monday – Friday<br>
                                08.00 – 17.00 WIB
                            </small>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Contact Form --}}
            <div class="col-lg-7">
                <div class="contact-form-card">
                    <div class="contact-form-heading">
                        <span class="form-badge">
                            Conference Inquiry
                        </span>
                        <h3>
                            Send Us a Message
                        </h3>
                        <p>
                            Fill out the form below and our team
                            will get back to you as soon as possible.
                        </p>
                    </div>


                    <form action="#" method="POST">
                        <div class="row g-4">
                            {{-- Name --}}
                            <div class="col-md-6">
                                <label for="contact_name">
                                    Full Name
                                </label>
                                <input type="text" id="contact_name" name="name" class="form-control"
                                    placeholder="Your full name">
                            </div>


                            {{-- Email --}}
                            <div class="col-md-6">
                                <label for="contact_email">
                                    Email Address
                                </label>
                                <input type="email" id="contact_email" name="email" class="form-control"
                                    placeholder="you@example.com">
                            </div>


                            {{-- Subject --}}
                            <div class="col-12">
                                <label for="contact_subject">
                                    Subject
                                </label>
                                <input type="text" id="contact_subject" name="subject" class="form-control"
                                    placeholder="What can we help you with?">
                            </div>


                            {{-- Message --}}
                            <div class="col-12">
                                <label for="contact_message">
                                    Message
                                </label>
                                <textarea id="contact_message" name="message" class="form-control" rows="5"
                                    placeholder="Write your message here..."></textarea>
                            </div>

                            {{-- Submit --}}
                            <div class="col-12">
                                <button type="submit" class="btn contact-submit-btn">
                                    Send Message
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
