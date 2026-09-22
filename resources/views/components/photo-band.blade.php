@props(['image', 'legende' => null, 'hauteur' => '44vh'])

{{-- Respiration entre deux sections : une photo pleine largeur, très lentement
     agrandie. Le voile garde le texte lisible quelle que soit la photo. --}}
<figure class="bande-photo" style="--h:{{ $hauteur }}">
    <img src="{{ asset($image) }}"
         alt="{{ $legende ?? 'Maine Coon de la Chatterie du Temple des Fées' }}" loading="lazy">
    <span class="voile" aria-hidden="true"></span>
    @if($legende)
        <figcaption>{{ $legende }}</figcaption>
    @endif
</figure>
