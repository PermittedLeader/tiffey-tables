<div class="flex flex-row gap-2 flex-grow">
    <div class="flex-grow">
        <x-tiffey::input
        label="Search"
        wire:model="searchString"
        placeholder="{{ __('crud.common.search') }}"
        autocomplete="off"
        inBlock="true"
        @keyup.enter="$wire.search()"
    />
    </div>
    <div class="my-auto">
        <x-tiffey::button wire:click="search()">
            <x-tiffey::icon.search /> Search
        </x-tiffey::button>
    </div>
</div>