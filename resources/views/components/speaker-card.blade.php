<div class="speaker-card">
    <div class="speaker-image">
        <img src="{{ asset('assets/images/speaker/' . $image) }}" alt="{{ $name }}">
    </div>
    <div class="speaker-body">
        <span class="speaker-country">
            {{ $country }}
        </span>
        <h4 class="speaker-name">
            {{ $name }}
        </h4>
        <p class="speaker-university">
            {{ $university }}
        </p>
    </div>
</div>
