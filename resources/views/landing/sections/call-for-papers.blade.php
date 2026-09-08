<section class="call-paper section-padding" id="paper">

    <div class="container">

        <div class="row align-items-center g-5">

            {{-- Image --}}
            <div class="col-lg-6">

                <div class="paper-image-wrapper">

                    {{-- Floating Card 1 --}}
                    <div class="paper-floating paper-floating-1">

                        <div class="paper-floating-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>

                        <div>
                            <strong>Abstract Submission</strong>
                            <small>Follow Conference Template</small>
                        </div>

                    </div>


                    {{-- Main Image --}}
                    <img src="{{ asset('assets/images/papers/paper.webp') }}" class="img-fluid paper-image"
                        alt="Call for Papers BHAMADA ICON 2026">


                    {{-- Floating Card 2 --}}
                    <div class="paper-floating paper-floating-2">

                        <div class="paper-floating-icon">
                            <i class="bi bi-journal-check"></i>
                        </div>

                        <div>
                            <strong>Scientific Review</strong>
                            <small>Abstract Review</small>
                        </div>

                    </div>

                </div>

            </div>


            {{-- Content --}}
            <div class="col-lg-6">

                <span class="section-badge">
                    Call For Papers
                </span>


                <h2 class="paper-heading mt-3">

                    Share Your
                    <span>Research</span>
                    at
                    BHAMADA ICON 2026

                </h2>


                <p class="paper-description">

                    Submit your research and innovation to BHAMADA ICON 2026,
                    an international conference that provides a forum for
                    academics, researchers, students, and practitioners to
                    present research, exchange ideas, and strengthen
                    interdisciplinary collaboration for sustainable development.

                </p>


                {{-- Submission Timeline --}}
                <div class="mb-4">

                    <div class="row g-3">

                        <div class="col-sm-6">

                            <div class="paper-item">

                                <i class="bi bi-calendar-check"></i>

                                <div>

                                    <strong>
                                        Abstract Submission & Review
                                    </strong>

                                    <span>
                                        29 July–13 August 2026
                                    </span>

                                </div>

                            </div>

                        </div>


                        <div class="col-sm-6">

                            <div class="paper-item">

                                <i class="bi bi-file-earmark-check"></i>

                                <div>

                                    <strong>
                                        Full Paper Submission
                                    </strong>

                                    <span>
                                        16–22 August 2026
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Submission Highlights --}}
                <div class="paper-list">

                    <div class="paper-item">

                        <i class="bi bi-check-circle-fill"></i>

                        <span>
                            Scientific Committee Review
                        </span>

                    </div>


                    <div class="paper-item">

                        <i class="bi bi-book"></i>

                        <span>
                            Book of Abstract
                        </span>

                    </div>


                    <div class="paper-item">

                        <i class="bi bi-person-video3"></i>

                        <span>
                            Parallel Presentation Sessions
                        </span>

                    </div>


                    <div class="paper-item">

                        <i class="bi bi-award"></i>

                        <span>
                            Best Paper Award
                        </span>

                    </div>


                    <div class="paper-item">

                        <i class="bi bi-journal-richtext"></i>

                        <span>
                            Journal Publication Recommendation
                        </span>

                    </div>


                    <div class="paper-item">

                        <i class="bi bi-patch-check"></i>

                        <span>
                            Electronic Certificate
                        </span>

                    </div>

                </div>


                {{-- Conference Highlights --}}
                <div class="publication-badge">

                    <span>
                        <i class="bi bi-shield-check"></i>
                        Scientific Review
                    </span>

                    <span>
                        <i class="bi bi-book"></i>
                        Book of Abstract
                    </span>

                    <span>
                        <i class="bi bi-award"></i>
                        Best Paper
                    </span>

                    <span>
                        <i class="bi bi-journal-check"></i>
                        Journal Recommendation
                    </span>

                </div>


                {{-- CTA --}}
                <div class="paper-actions mt-4">

                    <a href="{{ route('register') }}" class="btn btn-register">

                        Submit Abstract

                    </a>


                    {{-- Template belum memiliki sumber data/file resmi --}}
                    <button type="button" class="btn btn-login"
                        onclick="Swal.fire({
                            icon: 'info',
                            title: 'Template Coming Soon',
                            text: 'The abstract and full paper templates will be made available here soon.',
                            confirmButtonText: 'OK'
                        })">

                        <i class="bi bi-download me-2"></i>

                        Download Template

                    </button>

                </div>

            </div>

        </div>

    </div>

</section>
