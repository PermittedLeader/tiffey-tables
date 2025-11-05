<x-tiffey::card collapsible="true" open="false">
    <x-slot name="header"> @lang('Filters') </x-slot>
    @foreach ($this->filterableColumns() as $column)
        {{ $column->renderFilter() }}
    @endforeach
    
    <x-slot name="actions"> 
        @if(!empty($this->appliedFilters))
        <x-tiffey::button wire:click="clearFilters()">Clear</x-tiffey::button> 
        @endif
        <x-tiffey::button wire:click="search()">Apply</x-tiffey::button> 
    </x-slot>

    <div class="block md:hidden">
        <x-tiffey::card>
            <x-tiffey::input.select label="Sort by" wire:model.live="sortBy">
                @foreach ($this->sortableColumns() as $column)
                    <option value="{{ $column->key }}">{{ $column->label }}</option>
                @endforeach
            </x-tiffey::input.select>
            <x-tiffey::input.select label="Sort direction" wire:model.live="sortDirection">
                <option value="asc">A-Z</option>
                <option value="desc">Z-A</option>
            </x-tiffey::input.select>
        </x-tiffey::card>
    </div>
    
</x-tiffey::card>