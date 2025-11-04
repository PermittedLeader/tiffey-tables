<div class="block md:hidden w-full">
    <div class="w-full border-l-4 border-l-transparent flex flex-row">
        @php
            $column = collect($this->visibleColumns())->first();
        @endphp
        <div class="text-sm p-2 text-left border-b-4" @click.stop="">
            @if($this->selectable)
                <x-tables::select-all />
            @endif
        </div>
        
        <div class="text-sm p-2 text-left border-b-4 flex-grow">
            {{ $column->label }}
        </div>
    </div>
    <div>
        @forelse ($this->pagedData() as $row)
            <x-tiffey::modal>
                <x-slot:button>
                    <div class="flex flex-row border-b border-l-4 border-l-transparent hover:border-l-brand-mid hover:bg-gray-50 dark:hover:bg-gray-800">
                        @if($this->selectable)
                            <div class="text-sm px-2 py-2 md:py-3 text-left" @click.stop="">
                                <x-tiffey::input.checkbox label="Select this row" inBlock="true" name="selectRow" wire:model="selectedIds" value="{{ $row->id }}" />
                            </div>
                        @endif
                        <div class="text-sm px-2 py-2 md:py-3 text-left flex-grow">
                            {{ $column->renderColumn(Arr::get($row,$column->key)) }}
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
        @empty
        <div class="text-center">
            <x-tables::no-results />
        </div>
        @endforelse
    </div>
</div>