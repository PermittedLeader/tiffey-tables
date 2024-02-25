<x-tiffey::button {{ $actionComponent->getAction($data) }} {{ $actionComponent->getColor() }}>
    <x-tiffey::icon icon="{{ $actionComponent->icon }}" label="{{ $actionComponent->title }}" />
    @if($actionComponent->showLabel)
        {{ $actionComponent->title }}
    @endif
</x-tiffey::button>
