<div>  
    @php
        $colCount = count($this->actions()) > 0 ? count($this->visibleColumns())+1 : count($this->visibleColumns());
        
    @endphp
    @if($this->hasMessages())
        <div>
            @foreach($this->messages() as $message)
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
            @if($this->isExportable)
            @can('export',$this->query()->getModel())
            <div class='my-1'>
                <x-tiffey::button wire:click="export()" class="h-full">
                    <x-tiffey::icon icon="fa-solid fa-file-excel" label="Save to Excel" /> Export
                </x-tiffey::button>
            </div>
            @endcan
            @endif
            @if($this->tableActions())
                <div class="my-1">
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
    <div wire:loading class="w-full py-4 h-full text-gray-700 dark:text-gray-50">
        <div class="h-full flex align-middle justify-center">
            <x-tables::loading />
        </div>
    </div>
    <div class="w-full overflow-x-auto">
        <table class="w-full">
            <thead class="">
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
                @if($this->actions() && !$this->detailOnly)
                    <th class="text-sm p-2 text-right border-b-4">
                        Actions
                    </th>
                @endif
            </thead>
            <tbody class="m-2">
                @forelse ($this->data() as $row)
                    <tr class="border-b border-l-4 border-l-transparent hover:border-l-brand-500 hover:bg-gray-50 dark:hover:bg-gray-800">
                        @foreach ($this->visibleColumns() as $column)
                            <td class="">
                                <div class="max-w-[1/{{ $colCount > 6 ? '6' : $colCount }}] p-3">
                                    {{ $column->renderColumn($row[$column->key]) }}
                                </div>
                            </td>
                        @endforeach
                        @if($this->actions() && !$this->detailOnly)
                            <td class="p-3 text-right">
                                <div class="flex flex-row gap-1 justify-end items-stretch">
                                @foreach ($this->actions() as $action)
                                    {{ $action->renderForRow($row) }}
                                @endforeach
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
        {{ $this->data()->links('tables::livewire.pagination') }}
    </div>
    @foreach ($this->modals() as $modal)
        {{ $modal }}
    @endforeach
</div>
