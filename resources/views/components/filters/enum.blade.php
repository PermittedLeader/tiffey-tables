<div>
    <x-tiffey::card open="false" collapsible="true">
        <x-slot name="header">{{ $column->label }}</x-slot>
        <div class="grid grid-cols-4 gap-2">
            @foreach ($column->cases() as $case)
                <div>
                    <x-tiffey::input.checkbox wire:model="appliedFilters.{{ $column->key }}.{{ $case->value }}" label="{{ class_implements($case, '\Permittedleader\Forms\Traits\Enums\DisplayString') ? $case->display() : $case->value }}" value="{{ $case->value }}" />
                </div>
            @endforeach
        </div>
        @if(!empty($this->appliedFilters[$column->key]))
            <x-slot name="actions"> 
                <x-tiffey::button wire:click="clearFilters('{{ $column->key }}')">Clear</x-tiffey::button> 
            </x-slot>
        @endif
    </x-tiffey::card>
</div>