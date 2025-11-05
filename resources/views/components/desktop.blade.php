<div class="hidden md:block w-full overflow-x-auto">
    <table class="w-full">
        <thead class="border-l-4 border-l-transparent">
            @if($this->selectable)
            <x-tables::select-all />
            @endif
            @foreach ($this->visibleColumns() as $column)
                @if ($column->sortable)
                    <th class="text-sm p-2 text-left border-b-4" wire:click="sort('{{ $column->dbField() }}')">
                        {{ $column->label }}
                        @if ($this->sortBy === $column->key)
                            @if ($this->sortDirection === 'asc')
                                <x-tiffey::icon icon="fa-solid fa-sort-up" label="Sort Ascending" />
                            @else
                                <x-tiffey::icon icon="fa-solid fa-sort-down" label="Sort Descending" />
                            @endif
                        @else
                            <x-tiffey::icon icon="fa-solid fa-sort" label="Sort" />
                        @endif
                    </th>
                @else
                <th class="text-sm p-2 text-left border-b-4">
                    {{ $column->label }}
                </th>
                @endif
            @endforeach
            @if($this->actionsToRender() && !$this->detailOnly)
                <th class="text-sm p-2 text-right border-b-4">
                    
                </th>
            @endif
        </thead>
        <tbody class="m-2">
            @forelse ($this->pagedData() as $row)
                <tr class="border-b border-l-4 border-l-transparent hover:border-l-brand-mid hover:bg-gray-50 dark:hover:bg-gray-800" wire:key="{{ $row->id }}">
                    @if($this->selectable)
                        <td class="text-sm px-2 py-2 md:py-3 text-left">
                            <x-tiffey::input.checkbox label="Select this row" inBlock="true" name="selectRow" wire:model="selectedIds" value="{{ $row->id }}" />
                        </td>
                    @endif
                    @foreach ($this->visibleColumns() as $column)
                        <td class="">
                            <div class="max-w-[1/{{ max(count($this->visibleColumns()),6) }}] p-2 md:p-3">
                                @if($column->key == '*')
                                    {{ $column->renderColumn($row) }}
                                @else
                                    {{ $column->renderColumn(Arr::get($row,$column->key)) }}
                                @endif
                            </div>
                        </td>
                    @endforeach
                    @if($this->actionsToRender() && !$this->detailOnly)
                        <td class="p-2 md:p-3 text-right">
                            <div class="flex flex-row gap-1 justify-end items-stretch">
                                @php
                                    $actions = [];
                                    foreach ($this->actionsToRender() as $action) {
                                    $actions[] = $action->renderForRow($row);
                                    }
                                    $actions = collect($actions)->filter();
                                @endphp
                                {{ $actions->first() }}
                                @if(count($actions) > 1)
                                <x-tiffey::menu>
                                    <x-slot:button>
                                        <x-tiffey::button>
                                            <x-tiffey::icon icon="fa-solid fa-ellipsis" label="{{ __('tables::tables.more') }}" />
                                        </x-tiffey::button>
                                    </x-slot:button>
                                    @foreach ($actions as $action)
                                        {{ $action }}
                                    @endforeach
                                </x-tiffey::menu>
                                @endif
                            </div>
                        </td>
                    @endif
                </tr>
            @empty
            <tr>
                <td 
                    colspan="{{ count($this->visibleColumns()) }}"
                    class="text-center">
                    <x-tables::no-results />
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>