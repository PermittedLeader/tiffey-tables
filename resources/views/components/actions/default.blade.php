@if($actionComponent->route)
    <x-tiffey::button
        href="{{ $actionComponent->getRoute($data) }}"
        >
        <x-tiffey::icon icon="{{ $actionComponent->icon }}" label="{{ $actionComponent->title }}" />
    </x-tiffey::button>
@else
    <x-tiffey::button wire:click="{{ $actionComponent->action }}" >
        <x-tiffey::icon icon="{{ $actionComponent->icon }}" label="{{ $actionComponent->title }}" />
        @if($actionComponent->showLabel)
            {{ $actionComponent->title }}
        @endif
    </x-tiffey::button>
@endif
