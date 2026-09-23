{{--
    Ce qui attend : la liste de ce qui réclame une main, et rien d'autre.

    Les tons ne sont pas décoratifs. « urgent » est ce qu'un visiteur attend
    en ce moment même — un message, une demande d'adoption. « attention » est
    ce qui rend le site moins juste sans bloquer personne. « normal » est ce
    qui peut attendre demain.
--}}
@php
    $taches = $this->getTaches();
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Ce qui attend</x-slot>

        <x-slot name="description">
            @if($taches)
                {{ count($taches) }} {{ count($taches) > 1 ? 'points demandent' : 'point demande' }} votre attention.
            @else
                Rien à faire pour le moment.
            @endif
        </x-slot>

        @if($taches)
            <ul class="fi-ta-ctn divide-y divide-gray-200 dark:divide-white/10">
                @foreach($taches as $t)
                    <li>
                        <a href="{{ $t['url'] }}"
                           class="flex items-start gap-4 px-1 py-4 transition hover:bg-gray-50 dark:hover:bg-white/5">

                            <span @class([
                                'mt-0.5 flex h-9 w-9 flex-none items-center justify-center rounded-full text-sm font-semibold',
                                'bg-danger-500/15 text-danger-600 dark:text-danger-400'   => $t['ton'] === 'urgent',
                                'bg-warning-500/15 text-warning-600 dark:text-warning-400' => $t['ton'] === 'attention',
                                'bg-gray-500/15 text-gray-600 dark:text-gray-300'          => $t['ton'] === 'normal',
                            ])>{{ $t['nombre'] }}</span>

                            <span class="min-w-0">
                                <span class="block text-sm font-medium text-gray-950 dark:text-white">
                                    {{ $t['titre'] }}
                                </span>
                                <span class="mt-0.5 block text-sm text-gray-500 dark:text-gray-400">
                                    {{ $t['detail'] }}
                                </span>
                            </span>

                            <span class="ms-auto mt-1 flex-none text-gray-400" aria-hidden="true">
                                <x-filament::icon icon="heroicon-m-chevron-right" class="h-5 w-5" />
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                Les fiches sont à jour, les messages sont traités et les mentions
                légales sont complètes. Rien ne vous attend.
            </p>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
