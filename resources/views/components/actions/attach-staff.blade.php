<x-tiffey::button wire:click="attachDepartmentAsStaff('{{ $data->id }}')" color="bg-success-light">
    <x-tiffey::icon.attach label="{{ $actionComponent->title }}" />
    {{ $actionComponent->title }}
</x-tiffey::button>