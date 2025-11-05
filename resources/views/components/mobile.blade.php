<div class="block md:hidden w-full">
    <div class="w-full flex flex-row border-b-4 border-l-4 border-l-transparent ">
        @if($this->selectable)
            <div class="text-sm px-1 py-2 md:py-3 text-left" @click.stop="">
                <x-tables::select-all />
            </div>
        @endif
        <div class="flex-grow grid grid-cols-{{ count($this->visibleColumns(mobile: true)) }} font-bold">
            
            @foreach ($this->visibleColumns(mobile: true) as $column)
                <div class="text-sm px-2 py-2 md:py-3 text-left  my-auto">
                    {{ $column->label }}
                </div>
            @endforeach
            
        </div>
        <div class="my-auto pr-2 justify-end">
            <div style="visibility: hidden">
                <x-tiffey::button title="{{ __('tables::tables.more') }}">
                    <x-tiffey::icon icon="fa-solid fa-ellipsis" label="{{ __('tables::tables.more') }}" />
                </x-tiffey::button>
            </div>
                
        </div>
    </div>
    <div class="">
        @forelse ($this->pagedData() as $row)
        <div class="">
            <x-tiffey::modal>
                <x-slot:button>
                    <div class="w-full flex flex-row border-b border-l-4 border-l-transparent hover:border-l-brand-mid hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer py-2">
                        @if($this->selectable)
                            <div class="text-sm px-1 py-2 md:py-3 text-left" @click.stop="">
                                <x-tiffey::input.checkbox label="Select this row" inBlock="true" name="selectRow" wire:model="selectedIds" value="{{ $row->id }}" />
                            </div>
                        @endif
                        <div class="flex-grow grid grid-cols-{{ count($this->visibleColumns(mobile: true)) }} ">
                            
                            @foreach ($this->visibleColumns(mobile: true) as $column)
                                <div class="text-sm px-2 py-2 md:py-3 text-left flex-grow my-auto">
                                    {{ $column->renderColumn(Arr::get($row,$column->key)) }}
                                </div>
                            @endforeach
                            
                        </div>
                        <div class="my-auto pr-2 justify-end">
                                <x-tiffey::button title="{{ __('tables::tables.more') }}">
                                    <x-tiffey::icon icon="fa-solid fa-ellipsis" label="{{ __('tables::tables.more') }}" />
                                </x-tiffey::button>
                        </div>
                    </div>
                </x-slot:button>
                @if($this->actionsToRender() && !$this->detailOnly)
                    <div class="p-2 md:p-3 text-right">
                        <div class="flex flex-row gap-1 justify-end items-stretch">
                            @php
                                $actions = [];
                                foreach ($this->actionsToRender() as $action) {
                                $actions[] = $action->showLabel()->renderForRow($row);
                                }
                                $actions = collect($actions)->filter();
                            @endphp
                            @foreach ($actions as $action)
                                {{ $action }}
                            @endforeach
                        </div>
                    </div>
                @endif
                <div>
                    @foreach ($this->visibleColumns() as $detailColumn)
                        <div class="border-b hover:bg-gray-50">
                            <div class="font-bold">
                                {{ $detailColumn->label }}
                            </div>
                            <div class="p-2 md:p-3">
                                @if($detailColumn->key == '*')
                                    {{ $detailColumn->renderColumn($row) }}
                                @else
                                    {{ $detailColumn->renderColumn(Arr::get($row,$detailColumn->key)) }}
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                
            </x-tiffey::modal>
        </div>
            
        @empty
        <div class="text-center">
            <x-tables::no-results />
        </div>
        @endforelse
    </div>
</div>