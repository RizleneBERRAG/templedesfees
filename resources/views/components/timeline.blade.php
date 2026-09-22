@props(['events'])

{{-- Le suivi de la portee. Le point vert marque l'age legal de cession. --}}
<ul class="timeline">
    @foreach($events as $e)
        <li @class(['now' => $e->est_jalon, 'done' => $e->est_fait && ! $e->est_jalon])>
            <span class="pt"></span>
            <span>
                <span class="when">{{ $e->quand() }}</span>
                <span class="what">{{ $e->libelle }}</span>
            </span>
        </li>
    @endforeach
</ul>
