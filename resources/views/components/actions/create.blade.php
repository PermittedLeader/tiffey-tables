<x-tiffey::button {{ $attributes->merge($actionComponent->getAction($data))->without('color') }} color="{{ $actionComponent->color ? $actionComponent->color 'bg-success-light' }}">
    <x-tiffey::icon icon="{{ $actionComponent->icon=='fa-solid fa-eye' ? 'fa-solid fa-pen-to-square' : $actionComponent->icon }}" label="{{ $actionComponent->title }}" />
    {{ $actionComponent->title }}
</x-tiffey::button> 