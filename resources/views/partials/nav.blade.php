@php
    $tel   = \App\Models\Setting::get('contact.telephone', '06 77 35 45 87');
    $telLien = \Illuminate\Support\Str::of($tel)->replace(' ', '')->replaceFirst('0', '+33');
@endphp

{{--
    La marque à gauche, les rubriques et le numéro à droite.

    Le fronton centré a été essayé : avec sept rubriques il ne tient pas — la
    marque n'est jamais au milieu et vient buter dans le menu. Trois zones
    alignées valent mieux qu'une symétrie qui ne tombe juste sur aucune largeur.
--}}
<header class="bandeau" id="bandeau">

    <a class="marque" href="{{ route('home') }}">
        <b class="or">Temple des Fées</b>
        <small>Maine Coon · Drôme</small>
    </a>

    <div class="barre-droite">
        <nav id="menu" aria-label="Navigation principale">
            <a href="{{ route('kittens.index') }}" @if(request()->routeIs('kittens.*')) aria-current="page" @endif>Nos chatons</a>
            <a href="{{ route('cats.index') }}"    @if(request()->routeIs('cats.*'))    aria-current="page" @endif>Nos chats</a>
            <a href="{{ route('breed') }}"         @if(request()->routeIs('breed'))     aria-current="page" @endif>Le Maine Coon</a>
            <a href="{{ route('adoption.create') }}" @if(request()->routeIs('adoption.*')) aria-current="page" @endif>Adopter</a>
            <a href="{{ route('gallery') }}"       @if(request()->routeIs('gallery'))   aria-current="page" @endif>Galerie</a>
            <a href="{{ route('faq') }}"           @if(request()->routeIs('faq'))       aria-current="page" @endif>Questions</a>
            <a href="{{ route('contact') }}"       @if(request()->routeIs('contact'))   aria-current="page" @endif>Contact</a>
            <a class="menu-tel" href="tel:{{ $telLien }}">{{ $tel }}</a>
        </nav>

        <a class="tel" href="tel:{{ $telLien }}">{{ $tel }}</a>
        <button class="cle" id="cle" type="button" aria-expanded="false" aria-controls="menu">Menu</button>
    </div>

    {{-- Jauge de lecture : un filet d'or sur le bord bas du bandeau, rempli par
         la position de la page elle-même. Ornement pur, absent des navigateurs
         qui ne connaissent pas animation-timeline. --}}
    <span id="jauge" aria-hidden="true"></span>
</header>
