<section class="topics section-padding" id="topics">

    <div class="section-decoration decoration-left"></div>
    <div class="section-decoration decoration-right"></div>


    <div class="container">

        <x-section-header badge="Conference Tracks" title="Research Themes of <span>BHAMADA ICON 2026</span>"
            description="Explore the five interdisciplinary conference tracks covering health, pharmaceutical and biomedical research, digital technology and artificial intelligence, business and innovation, and occupational health, safety, and environmental sustainability." />


        @php
            $conferenceTopics = $conference?->topics ?? collect();
        @endphp


        <div class="row g-4">

            @foreach ($conferenceTopics as $topic)
                <div class="col-lg-4 col-md-6">

                    <x-topic-card :icon="$topic->icon" :title="$topic->name" :description="$topic->description" />

                </div>
            @endforeach

        </div>

    </div>

</section>
