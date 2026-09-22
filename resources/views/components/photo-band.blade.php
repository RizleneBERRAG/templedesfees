@props(['image', 'legende' => null, 'hauteur' => '46vh'])

{{-- Respiration entre deux sections : une photo pleine largeur, légèrement animée. --}}
<section class="photoband" style="--band-h:{{ $hauteur }}">
    <img src="{{ asset($image) }}" alt="{{ $legende ?? 'Bengal de la chatterie Chatterie du Temple des Fées' }}" loading="lazy">
    <span class="photoband-scrim" aria-hidden="true"></span>
    @if($legende)
        <span class="photoband-cap">{{ $legende }}</span>
    @endif
</section>
