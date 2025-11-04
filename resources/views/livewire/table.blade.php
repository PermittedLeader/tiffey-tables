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
        <x-tables::search />
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
    <x-tables::filters />
    @endif
    @if($this->selectable)
    <x-tables::selection />
    @endif

    <x-tables::mobile />

    <x-tables::desktop />
    
    <div class="mt-2">
        {{ $this->pagedData()->links('tables::livewire.pagination') }}
    </div>
    @foreach ($this->modals() as $modal)
        {{ $modal }}
    @endforeach
</div>

