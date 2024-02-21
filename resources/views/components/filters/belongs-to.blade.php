<div>
    <x-tiffey::card open="false" collapsible="true">
        <x-slot name="header">{{ $column->label }}</x-slot>
        <div class="grid grid-cols-4 gap-2">
            @foreach ($column->models() as $model)
                <div>
                    <x-tiffey::input.checkbox wire:model="appliedFilters.{{ $column->key }}.{{ $model->{$model->getKeyName()} }}" label="{{ $model->{$column->displayAttribute} }}" name="{{ $model->{$column->displayAttribute} }}" />
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