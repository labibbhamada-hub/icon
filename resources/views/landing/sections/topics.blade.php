<section class="topics section-padding" id="topics">

    <div class="section-decoration decoration-left"></div>
    <div class="section-decoration decoration-right"></div>


    <div class="container">

        <x-section-header badge="Conference Tracks" title="Research Themes of <span>BHAMADA ICON 2026</span>"
            description="Explore the five interdisciplinary conference tracks covering health, pharmaceutical and biomedical research, digital technology and artificial intelligence, business and innovation, and occupational health, safety, and environmental sustainability." />


        @php

            $conferenceTopics = [
                [
                    'icon' => 'bi-heart-pulse',
                    'title' => 'Health Sciences and Healthcare Innovation',
                    'description' =>
                        'Nursing, midwifery, community health, clinical practice, health promotion, and healthcare innovation.',
                ],

                [
                    'icon' => 'bi-capsule',
                    'title' => 'Pharmaceutical and Biomedical Research',
                    'description' =>
                        'Pharmaceutical sciences, clinical pharmacy, pharmaceutical technology, drug discovery, herbal medicine, and biomedical research.',
                ],

                [
                    'icon' => 'bi-cpu',
                    'title' => 'Digital Technology, Artificial Intelligence and Smart Systems',
                    'description' =>
                        'Artificial intelligence, data science, information systems, software engineering, Internet of Things (IoT), and smart healthcare.',
                ],

                [
                    'icon' => 'bi-lightbulb',
                    'title' => 'Business, Entrepreneurship and Innovation',
                    'description' =>
                        'Digital business, entrepreneurship, innovation management, marketing, digital economy, and creative industry.',
                ],

                [
                    'icon' => 'bi-shield-check',
                    'title' => 'Occupational Health, Safety and Environmental Sustainability',
                    'description' =>
                        'Occupational health, occupational safety, environmental health, risk management, industrial safety, and environmental sustainability.',
                ],
            ];

        @endphp


        <div class="row g-4">

            @foreach ($conferenceTopics as $topic)
                <div class="col-lg-4 col-md-6">

                    <x-topic-card :icon="$topic['icon']" :title="$topic['title']" :description="$topic['description']" />

                </div>
            @endforeach

        </div>

    </div>

</section>
