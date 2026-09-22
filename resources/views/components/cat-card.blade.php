@props(['chat'])

<a class="repro" href="{{ route('cats.show', $chat) }}">
    <span class="im">
        <img src="{{ asset($chat->photo_principale) }}"
             alt="{{ $chat->nom }}, Maine Coon {{ \Illuminate\Support\Str::lower($chat->robe) }}"
             loading="lazy" width="1100" height="1467">
    </span>
    <span class="cap">
        <span class="mono">{{ $chat->role->libelle() }}</span>
        <h3>{{ $chat->nom }}</h3>
        <p>{{ $chat->robe }} · {{ \Illuminate\Support\Str::ucfirst($chat->sexe) }} · {{ $chat->annee_naissance }}</p>
    </span>
</a>
