@php
    $imageSource =
        $photo ??
        (isset($image) ? asset('assets/images/speaker/' . $image) : asset('assets/images/speaker/default.webp'));
@endphp

<div class="speaker-card">

    <div class="speaker-image">

        @if (!empty($photo))
            <img src="{{ $photo }}" alt="{{ $name }}">
        @elseif (!empty($image))
            <img src="{{ asset('assets/images/speaker/' . $image) }}" alt="{{ $name }}">
        @else
            <div class="d-flex align-items-center justify-content-center h-100">
                <i class="bi bi-person display-4"></i>
            </div>
        @endif

    </div>


    <div class="speaker-body">

        @if (!empty($country))
            <span class="speaker-country">
                {{ $country }}
            </span>
        @endif

        <h4 class="speaker-name">
            {{ $name }}
        </h4>

        <p class="speaker-university">
            {{ $university }}
        </p>

    </div>

</div>
