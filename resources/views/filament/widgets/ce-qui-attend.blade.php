{{--
    Ce qui attend : la liste de ce qui réclame une main, et rien d'autre.

    La mise en forme est écrite ici, en clair, et non en classes utilitaires.
    Filament livre une feuille de style compilée sur son propre inventaire :
    une classe qu'il n'utilise pas lui-même n'existe pas dans le fichier, et
    la mise en page tombe sans prévenir. Un thème sur mesure réglerait le
    problème, au prix d'une chaîne de compilation à entretenir pour un seul
    encart. Les couleurs, elles, viennent de ses variables : l'encart suit
    donc le mode clair et le mode sombre sans rien savoir d'eux.

    Les tons ne sont pas décoratifs. « urgent » est ce qu'un visiteur attend
    en ce moment même — un message, une demande d'adoption. « attention » est
    ce qui rend le site moins juste sans bloquer personne. « normal » est ce
    qui peut attendre demain.
--}}
@php
    $taches = $this->getTaches();
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Ce qui attend</x-slot>

        <x-slot name="description">
            @if($taches)
                {{ count($taches) }} {{ count($taches) > 1 ? 'points demandent' : 'point demande' }} votre attention.
            @else
                Rien à faire pour le moment.
            @endif
        </x-slot>

        <style>
            .attend-liste{list-style:none;margin:0;padding:0}
            .attend-liste li + li{border-top:1px solid var(--gray-200, #e5e7eb)}
            .dark .attend-liste li + li{border-top-color:rgba(255,255,255,.1)}

            .attend-ligne{display:flex;align-items:flex-start;gap:1rem;
                padding:.95rem .25rem;text-decoration:none;
                transition:background-color .15s}
            .attend-ligne:hover{background-color:rgba(127,127,127,.07)}

            .attend-nombre{flex:none;width:2.25rem;height:2.25rem;border-radius:9999px;
                display:flex;align-items:center;justify-content:center;
                font-size:.875rem;font-weight:600;line-height:1}
            .attend-urgent   {background:rgba(239,68,68,.15);  color:#dc2626}
            .attend-attention{background:rgba(245,158,11,.15); color:#b45309}
            .attend-normal   {background:rgba(107,114,128,.15);color:#4b5563}
            .dark .attend-urgent   {color:#f87171}
            .dark .attend-attention{color:#fbbf24}
            .dark .attend-normal   {color:#d1d5db}

            .attend-texte{min-width:0;display:flex;flex-direction:column;gap:.15rem}
            .attend-titre{font-size:.875rem;font-weight:600;color:var(--gray-950, #030712)}
            .dark .attend-titre{color:#fff}
            .attend-detail{font-size:.8125rem;line-height:1.5;color:var(--gray-500, #6b7280)}
            .dark .attend-detail{color:#9ca3af}

            .attend-fleche{margin-inline-start:auto;flex:none;align-self:center;
                color:var(--gray-400, #9ca3af);font-size:1.1rem;line-height:1}

            .attend-vide{margin:0;padding:1.4rem 0;text-align:center;
                font-size:.875rem;color:var(--gray-500, #6b7280)}
            .dark .attend-vide{color:#9ca3af}
        </style>

        @if($taches)
            <ul class="attend-liste">
                @foreach($taches as $t)
                    <li>
                        <a class="attend-ligne" href="{{ $t['url'] }}">
                            <span class="attend-nombre attend-{{ $t['ton'] }}">{{ $t['nombre'] }}</span>

                            <span class="attend-texte">
                                <span class="attend-titre">{{ $t['titre'] }}</span>
                                <span class="attend-detail">{{ $t['detail'] }}</span>
                            </span>

                            <span class="attend-fleche" aria-hidden="true">&rsaquo;</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="attend-vide">
                Les fiches sont à jour, les messages sont traités et les mentions
                légales sont complètes. Rien ne vous attend.
            </p>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
