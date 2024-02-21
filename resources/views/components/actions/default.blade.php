<x-tiffey::button
    href="{{ $actionComponent->getRoute($data) }}"
    >
    <x-tiffey::icon icon="{{ $actionComponent->icon }}" label="{{ $actionComponent->title }}" />
</x-tiffey::button>