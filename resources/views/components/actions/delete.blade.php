<form
    action="{{ $actionComponent->getRoute($data) }}"
    method="POST"
    onsubmit="return confirm('{{ __('tables::tables.actions.confirm') }}')"
>
    @csrf @method('DELETE')
    <x-tiffey::form-button
        type="submit"
        color="bg-danger-light"
        title="{{ $actionComponent->title }}"
    >
    <x-tiffey::icon.delete label="{{ $actionComponent->title }}" />
    </x-tiffey::form-button>
</form>