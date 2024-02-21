
<form
    action="{{ $actionComponent->getRoute($data) }}"
    method="POST"
    onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')"
>
    @csrf @method('DELETE')
    <x-tiffey::form-button
        type="submit"
        color="bg-danger-100"
    >
    <x-tiffey::icon.delete label="{{ $actionComponent->title }}" />
    </x-tiffey::form-button>
</form>