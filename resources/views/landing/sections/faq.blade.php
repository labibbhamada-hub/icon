<section id="faq" class="faq-template">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="faq-template-title">Event FAQs</h3>
            </div>
        </div>

        @php
            $conferenceTitle = $conference?->short_name ?? 'BHAMADA ICON';
            $conferenceYear = $conference?->year ?? 2026;
            $conferenceDate = null;

            if ($conference?->start_date) {
                $conferenceDate = $conference->start_date->translatedFormat('d F Y');

                if ($conference->end_date && $conference->end_date->ne($conference->start_date)) {
                    $conferenceDate .= ' – ' . $conference->end_date->translatedFormat('d F Y');
                }
            }

            $conferenceLocation = collect([$conference?->venue, $conference?->city, $conference?->country])
                ->filter()
                ->implode(' — ');

            $registrationTypes = $conference?->registrationTypes ?? collect();
            $registrationNames = $registrationTypes->pluck('name')->filter()->values();

            $faqs = [
                [
                    'question' => 'What is ' . $conferenceTitle . ' ' . $conferenceYear . '?',
                    'answer' => 'BHAMADA ICON ' . $conferenceYear . ' is an international conference organized by Universitas Bhamada Slawi to bring together academics, researchers, students, and practitioners for research dissemination, knowledge exchange, and interdisciplinary collaboration.',
                ],
                [
                    'question' => 'How can I register for the conference?',
                    'answer' => 'Conference registration is completed through the BHAMADA ICON registration system. Select the registration option that matches your participation and continue through the registration workflow.',
                ],
                [
                    'question' => 'Can I submit my research to BHAMADA ICON?',
                    'answer' => 'Yes. BHAMADA ICON provides a submission pathway for research contributions. Eligible participants can use the conference registration and participant workflow to continue with their submission.',
                ],
                [
                    'question' => 'When and where will the conference take place?',
                    'answer' => trim(collect([
                        $conferenceDate ? 'The scheduled conference date is ' . $conferenceDate . '.' : null,
                        $conferenceLocation ? 'The listed conference location is ' . $conferenceLocation . '.' : 'The conference format and location will follow the information published by the committee.',
                    ])->filter()->implode(' ')),
                ],
                [
                    'question' => 'I have specific questions that are not addressed here. Who can help me?',
                    'answer' => 'Please use the contact information published in the Contact section of this website for questions about registration, submissions, payment, participation, or other conference matters.',
                ],
            ];
        @endphp

        <div class="row">
            <div class="col-md-12">
                <div class="faq-template-accordion" id="faqAccordion">
                    @foreach ($faqs as $index => $faq)
                        @php
                            $headingId = 'faqHeading' . ($index + 1);
                            $collapseId = 'faqCollapse' . ($index + 1);
                            $isFirst = $index === 0;
                        @endphp

                        <div class="faq-template-item">
                            <h4 class="faq-template-question" id="{{ $headingId }}">
                                <button
                                    class="faq-template-toggle {{ $isFirst ? '' : 'collapsed' }}"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#{{ $collapseId }}"
                                    aria-expanded="{{ $isFirst ? 'true' : 'false' }}"
                                    aria-controls="{{ $collapseId }}"
                                >
                                    {{ $faq['question'] }}
                                </button>
                            </h4>

                            <div
                                id="{{ $collapseId }}"
                                class="accordion-collapse collapse {{ $isFirst ? 'show' : '' }}"
                                aria-labelledby="{{ $headingId }}"
                                data-bs-parent="#faqAccordion"
                            >
                                <div class="faq-template-answer">
                                    {{ $faq['answer'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
