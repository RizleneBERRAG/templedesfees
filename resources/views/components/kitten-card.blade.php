@props(['chaton'])

<a class="fiche @if($chaton->statut === \App\Enums\KittenStatus::Adopte) partie @endif"
   href="{{ route('kittens.show', $chaton) }}"
   data-statut="{{ $chaton->statut->value }}">
    <span class="arche petite">
        <span class="pastille {{ $chaton->statut->value }}">{{ $chaton->statut->libelle() }}</span>
        <i><u>
            <x-img :src="$chaton->photo_principale"
                   alt="{{ $chaton->nom }}, chaton Maine Coon {{ \Illuminate\Support\Str::lower($chaton->robe) }}"
                   sizes="(max-width:560px) 88vw, (max-width:980px) 42vw, 26vw" :largeur="1200" :hauteur="1500" />
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
