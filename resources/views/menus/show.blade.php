<x-7xl>
    <x-slot name="header">
        <div class="inline-flex items-center">
            {!! $menu->name_with_art !!}
        </div>
    </x-slot>
    <div class="p-4 justify-even">

        <div class="grid grid-cols-3 sm:grid-cols-5 lg:grid-cols-8 gap-y-2 gap-x-0">
            @forelse($menu->children as $item)
                <x-article href="{{ $item->link }}" @if($item->type == 'external_link') target="_blank" @endif >

                    {!! $item->icon->art !!}

                    <x-slot name="caption">
                        {{ $item->name ?? $item->link }}
                    </x-slot>
                </x-article>
            @empty
                <x-article>

                    <svg class="h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>

                    <x-slot name="caption">
                        {{__('No Items')}}
                    </x-slot>
                </x-article>
            @endforelse
        </div>
    </div>
</x-7xl>

