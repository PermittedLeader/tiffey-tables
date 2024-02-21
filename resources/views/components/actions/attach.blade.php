
    @if(is_object($data))
        <x-tiffey::button wire:click="attach('{{ addslashes(get_class($data)) }}','{{ $data->id }}')" color="bg-success-100">
            <x-tiffey::icon.attach label="{{ $actionComponent->title }}" />
            {{ $actionComponent->title }}
        </x-tiffey::button>
    @else
        <x-tiffey::button wire:click="{{ $actionComponent->action }}" color="bg-success-100">
            <x-tiffey::icon.attach label="{{ $actionComponent->title }}" />
            {{ $actionComponent->title }}
        </x-tiffey::button>
    @endif