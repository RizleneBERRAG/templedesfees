@props(['cle', 'defaut' => null])

{{-- Affiche une mention legale, ou "À compléter" en bronze tant qu'elle est vide. --}}
@php($valeur = \App\Models\Setting::get($cle, $defaut))

@if(filled($valeur))
    <span class="v">{{ $valeur }}</span>
@else
    <span class="v todo">À compléter</span>
@endif
