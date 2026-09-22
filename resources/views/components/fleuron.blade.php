@props(['taille' => 'moyen'])

{{--
    Le fleuron du Temple des Fées : une graine centrale encadrée de deux
    palmes. Dessiné au trait plutôt que posé en image — quelques centaines
    d'octets, net à toutes les tailles, et il prend la couleur courante,
    donc il fonctionne aussi bien en or sur la nuit qu'en ombre sur un
    fond clair.

    Trois tailles : « petit » pour une fin de bloc, « moyen » pour un
    titre de chapitre, « grand » pour l'ouverture d'une page.
--}}

@php
    $mesures = [
        'petit'  => ['w' => 40,  'h' => 14, 'vb' => '0 0 40 14'],
        'moyen'  => ['w' => 96,  'h' => 26, 'vb' => '0 0 96 26'],
        'grand'  => ['w' => 150, 'h' => 30, 'vb' => '0 0 150 30'],
    ];
    $m = $mesures[$taille] ?? $mesures['moyen'];
@endphp

@if($taille === 'petit')
    <svg {{ $attributes }} width="{{ $m['w'] }}" height="{{ $m['h'] }}" viewBox="{{ $m['vb'] }}"
         fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" aria-hidden="true">
        <path d="M20 2c-4 3-4 7 0 10 4-3 4-7 0-10Z" />
        <path d="M14 7H2M38 7H26" />
    </svg>
@elseif($taille === 'grand')
    <svg {{ $attributes }} width="{{ $m['w'] }}" height="{{ $m['h'] }}" viewBox="{{ $m['vb'] }}"
         fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" aria-hidden="true">
        <path d="M75 6c-6 5-6 13 0 18 6-5 6-13 0-18Z" />
        <circle cx="75" cy="15" r="2.4" fill="currentColor" stroke="none" />
        <path d="M63 15c-8 0-12-7-20 0 8 7 12 0 20 0Z" />
        <path d="M87 15c8 0 12-7 20 0-8 7-12 0-20 0Z" />
        <path d="M35 15H6M144 15h-29" />
        <circle cx="39" cy="15" r="1.8" />
        <circle cx="111" cy="15" r="1.8" />
    </svg>
@else
    <svg {{ $attributes }} width="{{ $m['w'] }}" height="{{ $m['h'] }}" viewBox="{{ $m['vb'] }}"
         fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" aria-hidden="true">
        <path d="M48 3C48 9 44 12 40 13c4 1 8 4 8 10 0-6 4-9 8-10-4-1-8-4-8-10Z" />
        <path d="M34 13c-6 0-10-4-14 0 4 4 8 0 14 0Z" />
        <path d="M62 13c6 0 10-4 14 0-4 4-8 0-14 0Z" />
        <path d="M14 13H2M94 13H82" />
    </svg>
@endif
