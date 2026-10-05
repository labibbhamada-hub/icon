@php
    $faqs = [
        [
            'question' => 'How can I register for BHAMADA ICON?',
            'answer' =>
                'Use the Register button or the Registration section on this page to access the conference registration system.',
        ],
        [
            'question' => 'How do I submit an abstract or paper?',
            'answer' =>
                'Start from the conference registration flow. Submission requirements and the available workflow are provided to registered participants.',
        ],
        [
            'question' => 'Is BHAMADA ICON held online?',
            'answer' =>
                'The conference is conducted online through the virtual meeting platform designated by the conference committee.',
        ],
        [
            'question' => 'Where can I find the conference schedule?',
            'answer' =>
                'The Event Schedule section lists the important conference milestones and their corresponding dates.',
        ],
        [
            'question' => 'Where can I find registration fees and categories?',
            'answer' =>
                'The Registration & Pricing section displays the active registration categories, fees, payment timing, included papers, and benefits.',
        ],
    ];
@endphp

<section id="faq" class="section faq-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="section-title">Event FAQs</h3>
            </div>
        </div>

        <div class="accordion faq-accordion" id="eventFaq">
            @foreach ($faqs as $index => $faq)
                <div class="accordion-item rounded-0">
                    <h2 class="accordion-header" id="faq-heading-{{ $index }}">
                        <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }} rounded-0" type="button"
                            data-bs-toggle="collapse" data-bs-target="#faq-collapse-{{ $index }}"
                            aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                            aria-controls="faq-collapse-{{ $index }}">
                            {{ $faq['question'] }}
                        </button>
                    </h2>
                    <div id="faq-collapse-{{ $index }}"
                        class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                        aria-labelledby="faq-heading-{{ $index }}" data-bs-parent="#eventFaq">
                        <div class="accordion-body">
                            {{ $faq['answer'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
