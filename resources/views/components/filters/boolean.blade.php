<div>
    <x-tiffey::card open="false" collapsible="true">
        <x-slot name="header">{{ $column->label }}</x-slot>
        <div class="grid grid-cols-3 gap-2">
            <x-tiffey::input.select wire:model="appliedFilters.{{ $column->key }}" label="{{ $column->label }}">
                <option value="any" selected>Please choose an option...</option>
                <option value="1">True</option>
                <option value="false">False</option>
            </x-tiffey::input.select>
        </div>
        @if(!empty($this->appliedFilters[$column->key]))
            <x-slot name="actions"> 
                <x-tiffey::button wire:click="clearFilters('{{ $column->key }}')">Clear</x-tiffey::button> 
            </x-slot>
        @endif
    </x-tiffey::card>
</div>