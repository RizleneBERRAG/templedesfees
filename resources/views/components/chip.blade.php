@props(['statut'])

<span {{ $attributes->merge(['class' => 'pastille '.$statut->value]) }}>{{ $statut->libelle() }}</span>
