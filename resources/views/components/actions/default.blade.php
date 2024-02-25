<x-tiffey::button {{ $actionComponent->getAction($data) }} >
    <x-tiffey::icon icon="{{ $actionComponent->icon }}" label="{{ $actionComponent->title }}" />
    @if($actionComponent->showLabel)
        {{ $actionComponent->title }}
    @endif
</x-tiffey::button>
