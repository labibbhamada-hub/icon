<div class="section-header {{ $align ?? '' }}">
    @isset($badge)
        <span class="section-badge">
            {{ $badge }}
        </span>
    @endisset
    <h2 class="section-title">
        {!! $title !!}
    </h2>
    @isset($description)
        <p class="section-description">
            {{ $description }}
        </p>
    @endisset
</div>
