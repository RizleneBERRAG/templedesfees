@props(['chat'])

{{-- Les etiquettes servent au tri dans la page, quand aucun serveur ne
     peut le faire — voir le bloc « filtres d'une liste » du script. --}}
<a class="fiche" href="{{ route('cats.show', $chat) }}"
   data-role="{{ $chat->role->value }}" data-sexe="{{ $chat->sexeEnAdresse() }}">
    <span class="arche petite">
        @if($chat->role === \App\Enums\CatRole::Retraite)
            <span class="pastille adopte">Retraité</span>
        @endif
        <i><u>
            <x-img :src="$chat->photo_principale"
                   alt="{{ $chat->nom }}, Maine Coon {{ \Illuminate\Support\Str::lower($chat->robe) }}"
                   sizes="(max-width:560px) 88vw, (max-width:980px) 42vw, 26vw" :largeur="1200" :hauteur="1500" />
        </u></i>
    </span>
    <span class="bd">
        <b>{{ $chat->nom }}</b>
        <small>{{ $chat->robe }}</small>
        <span class="rubrique" style="letter-spacing:.2em">{{ $chat->role->libelle() }} · {{ $chat->annee_naissance }}</span>
    </span>
</a>
