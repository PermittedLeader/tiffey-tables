@props([])
<x-tiffey::button {{ $attributes->merge($actionComponent->getAction($data)) }}>
    <x-tiffey::icon.create label="{{ $actionComponent->title }}" />
    {{ $actionComponent->title }}
</x-tiffey::button> 