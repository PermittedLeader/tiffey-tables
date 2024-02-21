<div>
    <x-tiffey::card open="false" collapsible="true">
        <x-slot name="header">{{ $column->label }}</x-slot>
        <x-tiffey::input label="{{ $column->label }}" wire:model="appliedFilters.{{ $column->key }}" />
        @if(!empty($this->appliedFilters[$column->key]))
            <x-slot name="actions"> 
                <x-tiffey::button wire:click="clearFilters('{{ $column->key }}')">Clear</x-tiffey::button> 
            </x-slot>
        @endif
    </x-tiffey::card>
</div>