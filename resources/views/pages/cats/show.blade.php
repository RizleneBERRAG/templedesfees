@extends('layouts.app')

@section('title', $chat->nom.' — '.$chat->role->libelle())
@section('description', \Illuminate\Support\Str::limit(strip_tags($chat->description), 150))
@section('og_image', asset($chat->photo_principale))

@section('content')

<section class="band">
    <div class="wrap">
        <div class="shead" style="margin-bottom:30px">
            <x-rosettes class="rosettes rail" />
            <div class="txt">
                <span class="eyebrow">
                    <a href="{{ route('cats.index') }}" style="text-decoration:none">← L'élevage</a> · {{ $chat->role->libelle() }}
                </span>
                <h1>{{ $chat->nom }}</h1>
                <p class="lede">{{ $chat->description }}</p>
            </div>
        </div>

        <div class="detail">
            <div class="stack" style="gap:14px">
                <x-photo-viewer :photos="$chat->galerie()" />
            </div>

            <div class="stack" style="gap:22px">
                <x-record titre="Identité" meta="{{ $chat->role->libelle() }}">
                    <table>
                        <tr><th>Sexe</th><td>{{ \Illuminate\Support\Str::ucfirst($chat->sexe) }}</td></tr>
                        <tr><th>Année de naissance</th><td>{{ $chat->annee_naissance }}</td></tr>
                        <tr><th>Robe</th><td>{{ $chat->robe }}</td></tr>
                        <tr><th>Pedigree LOOF</th><td @class(['todo' => blank($chat->loof_numero)])>{{ $chat->loof_numero ?? 'À compléter' }}</td></tr>
                        <tr><th>Identification ICAD</th><td @class(['todo' => blank($chat->icad_numero)])>{{ $chat->icad_numero ?? 'À compléter' }}</td></tr>
                        <tr><th>Bilan santé</th>
                            <td>
                                @if($chat->bilanSanteComplet())
                                    <b style="color:var(--ok)">Complet</b>
                                @else
                                    <b style="color:var(--bronze-lt)">En cours</b><br>
                                    <span class="small" style="font-size:.79rem">Pas de mise à la reproduction tant qu'il n'est pas complet.</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </x-record>

                <x-health-table :chat="$chat" />

                @if($portees->isNotEmpty())
                    <x-record titre="Portées">
                        <table>
                            @foreach($portees as $portee)
                                <tr>
                                    <th>{{ $portee->code }} — {{ $portee->date_naissance->translatedFormat('F Y') }}</th>
                                    <td>{{ $portee->kittens_count }} chatons</td>
                                </tr>
                            @endforeach
                        </table>
                    </x-record>
                @endif

                <div class="btnrow">
                    <a class="btn ghost" href="{{ route('kittens.index') }}">Voir la portée en cours</a>
                </div>
            </div>
        </div>
    </div>
</section>

@if($autres->isNotEmpty())
<section class="band ink2">
    <div class="wrap">
        <x-section-head eyebrow="La lignée" titre="Les autres chats de l'élevage" />
        <div class="repros">
            @foreach($autres as $autre)
                <x-cat-card :chat="$autre" />
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
