<x-tiffey::button wire:click="attachDepartmentAsStaff('{{ $data->id }}')" color="bg-success-100">
    <x-tiffey::icon.attach label="{{ $actionComponent->title }}" />
    {{ $actionComponent->title }}
</x-tiffey::button>