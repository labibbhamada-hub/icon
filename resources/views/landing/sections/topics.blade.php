<section class="topics section-padding" id="topics">
    <div class="section-decoration decoration-left"></div>
    <div class="section-decoration decoration-right"></div>
    <div class="container">
        <x-section-header badge="Conference Topics" title="Research Themes of <span>ICON 2027</span>"
            description="Explore multidisciplinary topics covering health sciences, pharmacy, nursing, technology, public health, and interdisciplinary innovations." />
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <x-topic-card icon="{{ asset('assets/images/topics/health.png') }}" title="Health Sciences"
                    description="Innovation in healthcare services, medical sciences, and public health." />
            </div>
            <div class="col-lg-3 col-md-6">
                <x-topic-card icon="{{ asset('assets/images/topics/pharmacy.png') }}" title="Pharmacy"
                    description="Drug development, pharmaceutical technology, and clinical pharmacy." />
            </div>
            <div class="col-lg-3 col-md-6">
                <x-topic-card icon="{{ asset('assets/images/topics/nursing.png') }}" title="Nursing"
                    description="Evidence-based nursing practice and patient-centered care." />
            </div>
            <div class="col-lg-3 col-md-6">
                <x-topic-card icon="{{ asset('assets/images/topics/ai.png') }}" title="Technology"
                    description="Artificial intelligence, IoT, biomedical engineering, and digital innovation." />
            </div>
        </div>
    </div>
</section>
