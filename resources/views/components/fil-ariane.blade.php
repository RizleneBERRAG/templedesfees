@props(['etapes'])

{{--
    Le fil d'Ariane, en données structurées uniquement.

    Il n'est pas affiché : la page porte déjà un lien de retour en tête de
    chapitre (« ← Nos chats »), et doubler ce lien par une barre de navigation
    n'apprendrait rien au visiteur. Google, lui, s'en sert pour remplacer
    l'URL brute par le chemin de la page dans ses résultats — c'est la seule
    raison d'être de ce bloc.

    $etapes : un tableau ordonné [libellé => url], de la racine à la page
    courante. La dernière entrée est la page elle-même.
--}}

@php
    $fil = [
    '@context'        => 'https://schema.org',
    '@type'           => 'BreadcrumbList',
    'itemListElement' => collect($etapes)->values()->map(fn ($e, $i) => [
        '@type'    => 'ListItem',
        'position' => $i + 1,
        'name'     => $e['nom'],
        'item'     => $e['url'],
    ])->all(),
];
@endphp

<script type="application/ld+json">
{!! json_encode($fil, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
