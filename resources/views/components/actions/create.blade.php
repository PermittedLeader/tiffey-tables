<x-tiffey::button href="{{ $actionComponent->getRoute($data) }}" color="bg-success-100">
    <x-tiffey::icon.create label="{{ $actionComponent->title }}" />
    {{ $actionComponent->title }}
</x-tiffey::button> 