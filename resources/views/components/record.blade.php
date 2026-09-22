@props(['titre', 'meta' => null, 'note' => null])

<div {{ $attributes->merge(['class' => 'record']) }}>
    <div class="hd">
        <span class="mono">{{ $titre }}</span>
        @if($meta)<span class="mono" style="color:var(--ivory-dim)">{{ $meta }}</span>@endif
    </div>
    {{ $slot }}
    @if($note)<p class="note">{!! $note !!}</p>@endif
</div>
