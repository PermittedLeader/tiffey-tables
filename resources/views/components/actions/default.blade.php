@if($actionComponent->route)
<x-tiffey::button
    href="{{ $actionComponent->getRoute($data) }}"
    >
    <x-tiffey::icon icon="{{ $actionComponent->icon }}" label="{{ $actionComponent->title }}" />
</x-tiffey::button>
@else
@if(is_object($data))
        <x-tiffey::button wire:click="attach('{{ addslashes(get_class($data)) }}','{{ $data->id }}')" color="bg-success-light">
            <x-tiffey::icon icon="{{ $actionComponent->icon }}" label="{{ $actionComponent->title }}" />
            @if($actionComponent->showLabel)
                {{ $actionComponent->title }}
            @endif
        </x-tiffey::button>
    @else
        <x-tiffey::button wire:click="{{ $actionComponent->action }}" color="bg-success-100">
            <x-tiffey::icon icon="{{ $actionComponent->icon }}" label="{{ $actionComponent->title }}" />
            @if($actionComponent->showLabel)
                {{ $actionComponent->title }}
            @endif
        </x-tiffey::button>
    @endif
@endif
