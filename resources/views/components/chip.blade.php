@props(['statut'])

<span {{ $attributes->merge(['class' => 'chip '.$statut->classe()]) }}>
    <span class="dot"></span>{{ $statut->libelle() }}
</span>
