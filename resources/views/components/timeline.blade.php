@props(['events'])

{{-- Le suivi de la portée. Le point vert marque le jalon : l'âge légal de
     cession. Les points dorés sont les étapes déjà franchies. --}}
<ol class="chrono">
    @foreach($events as $e)
        <li @class(['encours' => $e->est_jalon, 'faite' => $e->est_fait && ! $e->est_jalon])>
            <span class="pt" aria-hidden="true"></span>
            <span>
                <span class="quand">{{ $e->quand() }}</span>
                <span class="quoi">{{ $e->libelle }}</span>
            </span>
        </li>
    @endforeach
</ol>
