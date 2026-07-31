<section class="speakers section-padding" id="speakers">
    <div class="container">
        <!-- Section Heading -->
        <div class="section-heading text-center">
            <span class="section-badge">
                Keynote Speakers
            </span>
            <h2 class="section-title mt-3">
                Meet Our
                <span>Distinguished Speakers</span>
            </h2>
            <p class="section-description">
                Learn from renowned professors, researchers, practitioners,
                and industry experts who are shaping the future of health,
                science, and technology.
            </p>
        </div>

        <!-- Featured Speaker -->
        <div class="featured-speaker">
            <div class="row align-items-center g-5">
                <!-- Speaker Photo -->
                <div class="col-lg-5">
                    <div class="featured-speaker-image">
                        <img src="{{ asset('assets/images/speaker/speaker-1.webp') }}" class="img-fluid"
                            alt="Keynote Speaker">
                    </div>
                </div>
                <!-- Speaker Info -->
                <div class="col-lg-7">
                    <span class="featured-label">
                        ★ Featured Keynote Speaker
                    </span>
                    <h3 class="featured-name">
                        Prof. Dr. John Doe
                    </h3>
                    <div class="featured-position">
                        Harvard Medical School
                    </div>
                    <p class="featured-description">
                        Internationally recognized researcher in health innovation,
                        artificial intelligence, and biomedical engineering with
                        more than 20 years of academic and research experience.
                    </p>
                    <div class="featured-tags">
                        <span>Artificial Intelligence</span>
                        <span>Digital Health</span>
                        <span>Biomedical Engineering</span>
                    </div>
                    <a href="#" class="btn btn-register mt-4">
                        View Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- Speaker Grid -->
        <div class="speaker-grid">
            <div class="row mt-5 g-4">
                <div class="col-lg-3 col-md-6">
                    <x-speaker-card image="speaker-2.webp" name="Prof. Maria Gonzalez"
                        university="National University of Singapore" country="Singapore" />
                </div>
                <div class="col-lg-3 col-md-6">
                    <x-speaker-card image="speaker-3.webp" name="Prof. James Wilson"
                        university="University of Melbourne" country="Australia" />
                </div>
                <div class="col-lg-3 col-md-6">
                    <x-speaker-card image="speaker-4.webp" name="Prof. Sarah Johnson" university="Kyoto University"
                        country="Japan" />
                </div>
                <div class="col-lg-3 col-md-6">
                    <x-speaker-card image="speaker-5.webp" name="Prof. Ahmad Fauzi" university="Universitas Indonesia"
                        country="Indonesia" />
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="#" class="btn btn-register">
                View All Speakers
            </a>
        </div>

    </div>
</section>
