    <x-tiffey::button
        href="{{ $actionComponent->getRoute($data) }}"
        >
        <x-tiffey::icon icon="fa-solid fa-trash-can-arrow-up" label="{{ $actionComponent->title }}" />
    </x-tiffey::button>