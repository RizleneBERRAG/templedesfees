@props([
    'url',
    'type'   => 'instagram',   {{-- instagram | facebook | mail | tel --}}
    'handle' => '@templedesfees',
])

{{--
    Icône seule, sans libellé. Au survol, l'anneau s'allume : aux couleurs de la
    marque pour Instagram, en bronze pour le courriel et le téléphone.

    Le pictogramme Instagram est le glyphe officiel, repris sans modification de
    son tracé : les règles de marque de Meta interdisent de le redessiner, de le
    recolorer autrement qu'en aplat uni, de l'incliner ou de le déformer.
    L'enveloppe et le combiné viennent de Heroicons (MIT), intégrés ici plutôt
    qu'appelés au paquet : celui-ci n'est installé que comme dépendance du
    back-office, et le site public ne doit pas en dépendre.

    L'anneau tourne au survol, le glyphe est contre-pivoté dans app.css pour
    rester droit — ne pas supprimer cette contre-rotation.
--}}

@php
    $externe = in_array($type, ['instagram', 'facebook'], true);

    [$titre, $intitule] = match ($type) {
        'mail' => ["Email — {$handle}",     "Écrire à {$handle}"],
        'tel'  => ["Téléphone — {$handle}", "Appeler le {$handle}"],
        'facebook' => ["Facebook — {$handle}", "Facebook {$handle} (nouvelle fenêtre)"],
        default => ["Instagram — {$handle}", "Instagram {$handle} (nouvelle fenêtre)"],
    };
@endphp

<a {{ $attributes->merge(['class' => 'social social--'.$type]) }}
   href="{{ $url }}"
   @if($externe) target="_blank" rel="noopener noreferrer" @endif
   title="{{ $titre }}"
   aria-label="{{ $intitule }}">
    <span class="social-mark" aria-hidden="true">
        @switch($type)
            @case('mail')
                <svg viewBox="0 0 24 24" fill="currentColor" focusable="false">
                    <path d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z"/>
                    <path d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z"/>
                </svg>
                @break

            @case('tel')
                <svg viewBox="0 0 24 24" fill="currentColor" focusable="false">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z"/>
                </svg>
                @break

            @case('facebook')
                <svg viewBox="0 0 24 24" fill="currentColor" focusable="false">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12S0 5.446 0 12.073c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073Z"/>
                </svg>
                @break

            @default
                <svg viewBox="0 0 24 24" fill="currentColor" focusable="false">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                </svg>
        @endswitch
    </span>
</a>
