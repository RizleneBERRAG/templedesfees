@extends('layouts.app')

@section('title', "Une erreur est survenue")
@section('description', "Une erreur technique empêche l'affichage de cette page.")

@push('head')
    <meta name="robots" content="noindex">
@endpush

@section('content')

{{-- Aucun détail technique ici : un message d'erreur ne doit rien révéler
     du fonctionnement interne à un visiteur. Les détails vont dans les logs. --}}
<section class="band">
    <div class="wrap" style="max-width:700px">
        <x-section-head
            niveau="1"
            eyebrow="Erreur"
            titre="Quelque chose s’est mal passé"
            lede="L’incident est enregistré de notre côté. Réessayez dans un instant — et si cela se reproduit, appelez-nous, nous répondons plus vite qu’un formulaire." />

        <div class="btnrow" style="margin-top:8px">
            <a class="btn" href="{{ route('home') }}">Retour à l’accueil</a>
            <a class="btn ghost" href="{{ route('contact') }}">Nous joindre</a>
        </div>
    </div>
</section>

@endsection
