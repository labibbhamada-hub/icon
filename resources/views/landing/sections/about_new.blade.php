@php
    $conferenceShortName = $conference?->short_name ?? 'BHAMADA ICON';
    $conferenceYear = $conference?->year ?? 2026;
    $conferenceTheme =
        $conference?->theme ?? 'Advancing Interdisciplinary Research and Innovation for Sustainable Development.';
    $conferenceName = $conference?->name ?? 'Bhamada International Conference on Interdisciplinary Innovation';
    $conferenceLocation = collect([$conference?->city, $conference?->country])
        ->filter()
        ->implode(', ');
@endphp

<section id="about" class="section about-section">
    <div class="container">
        <div class="row g-5 align-items-start">
            <div class="col-md-6">
                <h3 class="section-title">About Us</h3>
                <p>
                    {{ $conferenceName }} is an international scientific forum organized by Universitas Bhamada Slawi.
                    BHAMADA ICON {{ $conferenceYear }} brings together academics, researchers, students,
                    practitioners, and professionals to disseminate research, exchange ideas, and strengthen
                    interdisciplinary collaboration for sustainable development.
                </p>

                @if ($conferenceLocation)
                    <p class="mb-4">
                        <strong>Organizer Location:</strong> {{ $conferenceLocation }}
                    </p>
                @endif

                <figure class="mb-0">
                    <img alt="BHAMADA ICON" class="img-fluid w-100"
                        src="{{ asset('assets/images/landing/about-us.jpg') }}">
                </figure>
            </div>

            <div class="col-md-6">
                <h3 class="section-title">What is Our Goal?</h3>
                <p>
                    {{ $conferenceTheme }} The conference is designed to strengthen research and scientific publication,
                    expand academic networks, and encourage meaningful collaboration across higher education,
                    research organizations, industry, government, and other partners.
                </p>

                <ul class="list-arrow-right">
                    <li>Strengthen interdisciplinary research and knowledge exchange.</li>
                    <li>Disseminate research through conference presentations and scientific publication.</li>
                    <li>Expand national and international academic and professional networks.</li>
                    <li>Encourage innovation that contributes to sustainable development.</li>
                </ul>
            </div>
        </div>
    </div>
</section>
