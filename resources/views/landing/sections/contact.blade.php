<section id="contact" class="contact-section">

    @php

        $conferenceShortName = $conference?->short_name ?? 'BHAMADA ICON';

        $conferenceYear = $conference?->year ?? 2026;

        $conferenceDate = '27 August 2026';

        $conferenceMethod = 'Online Conference';

        $conferencePlatform = 'Zoom Meeting / Virtual Conference Platform';

    @endphp


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

                Have questions about
                {{ $conferenceShortName }}
                {{ $conferenceYear }}?

                Visit the registration and call-for-papers
                sections for information about participation,
                abstract submission, and conference activities.

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

                            <h4>
                                Conference Secretariat
                            </h4>

                            <p>
                                Information about BHAMADA ICON 2026.
                            </p>

                        </div>

                    </div>


                    <div class="contact-info-list">

                        {{-- Conference Method --}}
                        <div class="contact-info-item">

                            <div class="contact-item-icon">

                                <i class="bi bi-camera-video"></i>

                            </div>

                            <div>

                                <span>
                                    Conference Method
                                </span>

                                <strong>
                                    {{ $conferenceMethod }}
                                </strong>

                            </div>

                        </div>


                        {{-- Conference Platform --}}
                        <div class="contact-info-item">

                            <div class="contact-item-icon">

                                <i class="bi bi-laptop"></i>

                            </div>

                            <div>

                                <span>
                                    Conference Platform
                                </span>

                                <strong>
                                    {{ $conferencePlatform }}
                                </strong>

                            </div>

                        </div>


                        {{-- Conference Date --}}
                        <div class="contact-info-item">

                            <div class="contact-item-icon">

                                <i class="bi bi-calendar-event"></i>

                            </div>

                            <div>

                                <span>
                                    Conference Date
                                </span>

                                <strong>
                                    {{ $conferenceDate }}
                                </strong>

                            </div>

                        </div>

                    </div>


                    {{-- Conference Information --}}
                    <div class="contact-response-time">

                        <div class="response-icon">

                            <i class="bi bi-info-circle"></i>

                        </div>

                        <div class="response-content">

                            <span>
                                Conference Information
                            </span>

                            <strong>
                                Registration & Submission
                            </strong>

                            <small>
                                Please visit the Registration and
                                Call for Papers sections for
                                participation and submission details.
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
                            Online contact service will be available
                            for conference inquiries.
                        </p>

                    </div>


                    {{-- Backend contact form is not available yet --}}
                    <div class="text-center py-5">

                        <i class="bi bi-envelope-paper display-5 text-muted"></i>


                        <h5 class="mt-3 mb-2">
                            Contact Service Coming Soon
                        </h5>


                        <p class="text-muted mb-0">

                            The online contact form is not available yet.
                            Please refer to the conference sections above
                            for registration and submission information.

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>
