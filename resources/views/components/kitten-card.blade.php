@props(['chaton'])

<a class="fiche @if($chaton->statut === \App\Enums\KittenStatus::Adopte) gone @endif"
   href="{{ route('kittens.show', $chaton) }}">
    <span class="ph">
        <x-chip :statut="$chaton->statut" />
        <img src="{{ asset($chaton->photo_principale) }}"
             alt="{{ $chaton->nom }}, chaton Maine Coon {{ \Illuminate\Support\Str::lower($chaton->robe) }}"
             loading="lazy" width="900" height="1200">
    </span>
    <span class="bd">
        <span class="nm"><h3>{{ $chaton->nom }}</h3><span class="ref">{{ $chaton->reference }}</span></span>
        <dl>
            <dt>Sexe</dt><dd>{{ \Illuminate\Support\Str::ucfirst($chaton->sexe) }}</dd>
            <dt>Robe</dt><dd>{{ $chaton->robe }}</dd>
            <dt>Né le</dt><dd>{{ $chaton->litter->date_naissance->translatedFormat('j F Y') }}</dd>
        </dl>
        <span class="go">Voir la fiche complète →</span>
    </span>
</a>
