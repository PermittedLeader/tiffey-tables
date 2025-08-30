<div>  
    @php
        $colCount = count($this->actionsToRender()) > 0 ? count($this->visibleColumns())+1 : count($this->visibleColumns());
        
    @endphp
    @if($this->hasMessages($this->getMessageBagName()))
        <div>
            @foreach($this->messages($this->getMessageBagName()) as $message)
            <div wire:key="{{ \Illuminate\Support\Str::random() }}">
                <x-tiffey::alert level="{{ $message['level'] }}" dismissable="{{ $message['dismissable'] }}">
                    @if ($message['title'])
                        <x-slot name="header">
                            {{ $message['title'] }}
                        </x-slot>
                    @endif
                    @if ($message['actions'])
                        <x-slot name="actions">
                            {{ $message['actions'] }}
                        </x-slot>
                    @endif
                    {{ $message['message'] }}
                </x-tiffey::alert>
            </div>
            @endforeach
        </div>
    @endif
    <div class="flex flex-row gap-2 justify-between overflow-x-auto">
        @if ($this->isSearchable)
        <div class="flex flex-row gap-2 flex-grow">
            <div class="flex-grow">
                <x-tiffey::input
                label="Search"
                wire:model="searchString"
                placeholder="{{ __('crud.common.search') }}"
                autocomplete="off"
                inBlock="true"
            />
            </div>
            <div class="my-auto">
                <x-tiffey::button wire:click="search()">
                    <x-tiffey::icon.search /> Search
                </x-tiffey::button>
            </div>
        </div>
        @endif
        @if(!$this->detailOnly)
            @if($this->tableActions())
                <div class="my-auto">
                @foreach ($this->tableActions() as $action)
                    {{ $action->render() }}
                @endforeach
                </div>
            @endif
        @endif
    </div>
    @if($this->filterableColumns()&&$this->isFilterable)
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
    @endif
    @if($this->selectable)
    <div x-show="$wire.selectedIds.length > 0" x-cloak>
        <x-tiffey::card>
            <x-slot:header><span x-text="$wire.selectedIds.length"></span> selected</x-slot:header>
            <div class="flex flex-row justify-between gap-2">
                <div>
                    <span x-show="$wire.selectedIds.length < {{ $this->pagedData()->total() }}">
                        <x-tiffey::button wire:click="selectAllPages"> Select all pages </x-tiffey::button>
                    </span> 
                    <span x-show="$wire.selectedIds.length == {{ $this->pagedData()->total() }}">
                        <x-tiffey::button wire:click="selectAllPages"> Deselect all pages </x-tiffey::button>
                    </span>
                </div>
                <div class="flex flex-col md:flex-row gap-2">
                    @if($this->bulkActions())
                        <div class="my-auto">
                        @foreach ($this->bulkActions() as $action)
                            {{ $action->render() }}
                        @endforeach
                        </div>
                    @endif
                    @if($this->isExportable)
                        @can('export',$this->query()->getModel())
                            <div class='my-auto'>
                                <x-tiffey::button wire:click="export()">
                                    <x-tiffey::icon icon="fa-solid fa-file-excel" label="Save to Excel" /> Export
                                </x-tiffey::button>
                            </div>
                        @endcan
                    @endif
                </div>
            </div>
            
            
        </x-tiffey::card>
    </div>
    @endif
    <div class="w-full overflow-x-auto">
        <table class="w-full">
            <thead class="border-l-4 border-l-transparent">
                @if($this->selectable)
                <x-tables::select-all />
                @endif
                @foreach ($this->visibleColumns() as $column)
                    @if ($column->sortable)
                        <th class="text-sm p-2 text-left border-b-4" wire:click="sort('{{ $column->dbField() }}')">
                            {{ $column->label }}
                            @if ($sortBy === $column->key)
                                @if ($sortDirection === 'asc')
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
                                <div class="max-w-[1/{{ $colCount > 6 ? '6' : $colCount }}] p-2 md:p-3">
                                    @if($column->key == '*')
                                        {{ $column->renderColumn($row) }}
                                    @else
                                        {{ $column->renderColumn($row[$column->key]) }}
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
                                            <x-tiffey::icon icon="fa-solid fa-ellipsis" label="More..." />
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
                        colspan="{{ $colCount  }}"
                        class="text-center">
                        <x-tables::no-results />
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-2">
        {{ $this->pagedData()->links('tables::livewire.pagination') }}
    </div>
    @foreach ($this->modals() as $modal)
        {{ $modal }}
    @endforeach
</div>

