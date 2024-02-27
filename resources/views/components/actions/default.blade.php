@props([])
<x-tiffey::button {{ $attributes->merge($actionComponent->getAction($data)) }} >
    <x-tiffey::icon icon="{{ $actionComponent->icon }}" label="{{ $actionComponent->title }}" />
    @if($actionComponent->showLabel)
        {{ $actionComponent->title }}
    @endif
</x-tiffey::button>
