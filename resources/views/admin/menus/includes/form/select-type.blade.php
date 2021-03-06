<div class="col-span-6 sm:col-span-4">
    <x-jet-label for="type" value="{{ __('Type') }}" />
    <select
    x-on:change="menuType = $event.target.value"
    id="type"
    wire:model="state.type"
    class="block w-full mb-2 border-gray-300 rounded-md shadow-sm form-select focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
    >
        <option value="main_menu">{{ (isset($item) && $item) ? 'Link to selected menu' : 'Menu'}}</option>
        <option value="internal_link">Local Link</option>
        <option value="external_link">External Link</option>
    </select>
    <x-input-error for="type" class="mt-2" />
</div>
