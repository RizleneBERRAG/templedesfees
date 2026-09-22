@extends('layouts.app')

@section('title', $chaton->nom.' — chaton Maine Coon '.$chaton->statut->libelle())
@section('description', \Illuminate\Support\Str::limit(strip_tags($chaton->description), 150))
@section('og_image', asset($chaton->photo_principale))

@section('content')

<section class="band">
    <div class="wrap">
        <div class="shead" style="margin-bottom:30px">
            <x-rosettes class="rosettes rail" />
            <div class="txt">
                <span class="eyebrow">
                    <a href="{{ route('kittens.index') }}" style="text-decoration:none">← {{ $portee->code }}</a>
                    · Fiche {{ $chaton->reference }}
                </span>
                <h1 style="display:flex;align-items:center;gap:20px;flex-wrap:wrap">
                    {{ $chaton->nom }} <x-chip :statut="$chaton->statut" />
                </h1>
                <p class="lede">{{ $chaton->description }}</p>
            </div>
        </div>

        <div class="detail">
            <div class="stack" style="gap:14px">
                <x-photo-viewer :photos="$chaton->galerie()" />
                @if($fratrie->isNotEmpty())
                    <div class="grid" style="grid-template-columns:repeat(3,1fr);gap:12px">
                        @foreach($fratrie->take(3) as $frere)
                            <a class="figure" style="margin:0" href="{{ route('kittens.show', $frere) }}">
                                <img src="{{ asset($frere->photo_principale) }}"
                                     alt="{{ $frere->nom }}, de la même portée" loading="lazy" style="aspect-ratio:1">
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="stack" style="gap:22px">

                <x-record titre="Identité" meta="{{ $chaton->reference }}"
                          note="Les numéros LOOF et ICAD se saisissent depuis l'espace de gestion. Tant qu'ils sont vides, la fiche reste en brouillon et n'est pas publiée — c'est la règle imposée par la réglementation sur les annonces de cession.">
                    <table>
                        <tr><th>Sexe</th><td>{{ \Illuminate\Support\Str::ucfirst($chaton->sexe) }}</td></tr>
                        <tr><th>Date de naissance</th><td>{{ $portee->date_naissance->translatedFormat('j F Y') }}</td></tr>
                        <tr><th>Âge</th><td>{{ $chaton->ageEnSemaines() }} semaines</td></tr>
                        <tr><th>Robe</th><td>{{ $chaton->robe }}</td></tr>
                        @if($chaton->poidsFormate())
                            <tr><th>Poids au dernier contrôle</th><td style="font-variant-numeric:tabular-nums">{{ $chaton->poidsFormate() }}</td></tr>
                        @endif
                        <tr><th>Parents</th><td>{{ $portee->pere?->nom }} × {{ $portee->mere?->nom }}</td></tr>
                        <tr><th>Inscription</th><td>LOOF — pedigree remis au départ</td></tr>
                        <tr><th>N° de portée LOOF</th><td @class(['todo' => blank($portee->loof_portee_numero)])>{{ $portee->loof_portee_numero ?? 'À compléter' }}</td></tr>
                        <tr><th>Identification ICAD</th><td @class(['todo' => blank($chaton->icad_numero)])>{{ $chaton->icad_numero ?? 'À compléter' }}</td></tr>
                        <tr><th>Disponible à partir du</th><td>{{ $portee->date_disponibilite?->translatedFormat('j F Y') }}</td></tr>
                    </table>
                </x-record>

                <x-record titre="Pedigree" meta="3 générations"
                          note="Les générations précédentes se reprennent du pedigree LOOF. Elles permettent d'afficher le taux de consanguinité de la portée.">
                    <div class="tree">
                        @foreach([['Père', $portee->pere], ['Mère', $portee->mere]] as [$role, $parent])
                            <div class="gen">
                                @if($parent)
                                    <a class="cell" href="{{ route('cats.show', $parent) }}">
                                        <em>{{ $role }}</em>
                                        <strong>{{ $parent->nom }}</strong>
                                        <span>{{ $parent->robe }}</span>
                                    </a>
                                @else
                                    <div class="cell empty"><em>{{ $role }}</em><strong>À compléter</strong></div>
                                @endif
                                <div class="sub">
                                    <div class="cell empty"><em>Grand-père {{ $role === 'Père' ? 'paternel' : 'maternel' }}</em><strong>À compléter</strong></div>
                                    <div class="cell empty"><em>Grand-mère {{ $role === 'Père' ? 'paternelle' : 'maternelle' }}</em><strong>À compléter</strong></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-record>

                @if($portee->pere)<x-health-table :chat="$portee->pere" />@endif
                @if($portee->mere)<x-health-table :chat="$portee->mere" />@endif

                @if($portee->events->isNotEmpty())
                    <x-record titre="Suivi de la portée">
                        <x-timeline :events="$portee->events" />
                    </x-record>
                @endif

                @unless($chaton->estDisponible())
                    <div class="record">
                        <p class="note" style="border-top:0">
                            @if($chaton->statut === \App\Enums\KittenStatus::Reserve)
                                {{ $chaton->nom }} est réservé. Vous pouvez demander à être prévenu en priorité de la prochaine portée.
                            @else
                                {{ $chaton->nom }} a rejoint sa famille. Les fiches restent en ligne pour retracer le travail de l'élevage.
                            @endif
                        </p>
                    </div>
                    <div class="btnrow">
                        <a class="btn ghost" href="{{ route('kittens.index', ['statut' => 'disponible']) }}">Voir les chatons disponibles</a>
                    </div>
                @endunless
            </div>
        </div>
    </div>
</section>

<div class="band tight" style="padding-block:clamp(22px,3vw,36px)">
    <x-photo-strip titre="La portée en images" />
</div>

@if($chaton->estDisponible())
<div class="kbar">
    <div class="wrap in">
        <span class="nm">{{ $chaton->nom }}</span>
        <x-chip :statut="$chaton->statut" />
        <span class="small" style="font-size:.82rem">{{ \Illuminate\Support\Str::ucfirst($chaton->sexe) }} · {{ $chaton->robe }}</span>
        <a class="btn" href="{{ route('adoption.create', ['chaton' => $chaton->id]) }}">Pré-réserver {{ $chaton->nom }}</a>
    </div>
</div>
@endif

@endsection
