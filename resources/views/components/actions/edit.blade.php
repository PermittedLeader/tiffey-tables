    <x-tiffey::button
        href="{{ $actionComponent->getRoute($data) }}"
        >
        <x-tiffey::icon.edit label="{{ $actionComponent->title }}" />
    </x-tiffey::button>