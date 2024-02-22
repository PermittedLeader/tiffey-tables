<x-tiffey::button href="{{ $actionComponent->getRoute($data) }}" color="bg-success-light">
    <x-tiffey::icon.create label="{{ $actionComponent->title }}" />
    {{ $actionComponent->title }}
</x-tiffey::button> 