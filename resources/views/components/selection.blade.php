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