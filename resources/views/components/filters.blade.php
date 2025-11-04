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
    
</x-tiffey::card>