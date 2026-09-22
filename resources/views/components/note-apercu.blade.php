@props(['quoi' => 'Ce formulaire'])

{{--
    La note d'aperçu.

    Sur la version statique — celle publiée pour montrer le site — il n'y a
    pas de serveur derrière : un formulaire ne peut rien envoyer. Le dire
    avant plutôt qu'au moment du clic, et donner dans la foulée les deux
    moyens qui, eux, marchent : le téléphone et l'adresse électronique.
--}}
@php
    $tel   = \App\Models\Setting::get('contact.telephone');
    $email = \App\Models\Setting::get('contact.email');
@endphp

<p class="note-apercu">
    <span class="sceau" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3"
             stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="9"/><path d="M12 8h.01M11 12h1v4h1"/>
        </svg>
    </span>
    <span>
        <b>Aperçu de démonstration.</b>
        {{ $quoi }} est complet et fonctionnel, mais cette version est publiée sans serveur :
        rien n’est envoyé d’ici.
        @if($email)
            Écrivez-nous à <a href="mailto:{{ $email }}">{{ $email }}</a>@if($tel) ou appelez le
            <a href="tel:{{ \Illuminate\Support\Str::of($tel)->replace(' ', '')->replaceFirst('0', '+33') }}">{{ $tel }}</a>@endif.
        @endif
    </span>
</p>
