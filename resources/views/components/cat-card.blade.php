@props(['chat'])

<a class="fiche" href="{{ route('cats.show', $chat) }}">
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
