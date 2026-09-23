@props(['texte'])

{{--
    Un texte de réglage rendu en paragraphes.

    Les conditions de l'acompte et les textes du même ordre vivent en réglage,
    pour que l'éleveuse puisse les reprendre seule. Elle y écrit du texte
    simple, avec au plus des **passages en gras** — pas du HTML, qu'elle
    n'a aucune raison de connaître et qui ouvrirait une porte inutile.

    Le texte est donc échappé AVANT d'être enrichi : ce qu'elle tape ne peut
    jamais devenir une balise. C'est la même règle que pour le corps d'un
    article, et l'ordre des deux opérations n'est pas négociable.
--}}
@php
    $blocs = collect(preg_split('/\R{2,}/u', trim((string) $texte)))
        ->map(fn ($b) => trim($b))
        ->filter()
        ->map(function ($bloc) {
            $bloc = e($bloc);
            $bloc = preg_replace('/\*\*(.+?)\*\*/u', '<strong>$1</strong>', $bloc);

            return nl2br($bloc, false);
        });
@endphp

@if($blocs->isNotEmpty())
    <div {{ $attributes->merge(['class' => 'texte-reglage']) }}>
        @foreach($blocs as $bloc)
            <p>{!! $bloc !!}</p>
        @endforeach
    </div>
@endif
