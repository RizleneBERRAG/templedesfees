@props(['chaton'])

<a class="fiche @if($chaton->statut === \App\Enums\KittenStatus::Adopte) partie @endif"
   href="{{ route('kittens.show', $chaton) }}">
    <span class="arche petite">
        <span class="pastille {{ $chaton->statut->value }}">{{ $chaton->statut->libelle() }}</span>
        <i><u>
            <img src="{{ asset($chaton->photo_principale) }}"
                 alt="{{ $chaton->nom }}, chaton Maine Coon {{ \Illuminate\Support\Str::lower($chaton->robe) }}"
                 loading="lazy" width="1200" height="1500">
        </u></i>
    </span>
    <span class="bd">
        <b>{{ $chaton->nom }}</b>
        <small>{{ $chaton->robe }}</small>
        <span class="rubrique" style="letter-spacing:.2em">
            {{ \Illuminate\Support\Str::ucfirst($chaton->sexe) }} · {{ $chaton->reference }}
        </span>
    </span>
</a>
