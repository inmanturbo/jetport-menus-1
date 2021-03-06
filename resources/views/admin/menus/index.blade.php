<x-7xl>
    <x-slot name="header">
        {{__('Menus')}}
    </x-slot>

    <x-slot name="headerActions">
        @include('admin.menus.includes.header-actions')
    </x-slot>

    @include('admin.menus.includes.table')
</x-7xl>
<livewire:admin.menus.create />
<livewire:admin.menus.edit />
<livewire:admin.menus.delete />
<livewire:admin.menus.deactivate />

