@props(['titre', 'meta' => null, 'note' => null])

{{--
    Un registre : un en-tête, un tableau, et une note en bas.
    Les quatre équerres dorées sont posées en <span> plutôt qu'en border-image,
    parce que chaque angle doit mordre d'un pixel sur le filet pour paraître
    posé dessus.
--}}
<div {{ $attributes->merge(['class' => 'registre']) }}>
    <span class="eq" aria-hidden="true"></span><span class="eq" aria-hidden="true"></span>
    <span class="eq" aria-hidden="true"></span><span class="eq" aria-hidden="true"></span>

    <div class="entete">
        <b>{{ $titre }}</b>
        @if($meta)<span>{{ $meta }}</span>@endif
    </div>

    {{ $slot }}

    @if($note)<p class="note">{!! $note !!}</p>@endif
</div>
