<div class="flex flex-row justify-center">
    @if($this->isFilterable && !empty($this->appliedFilters))
    <div class="w-36 h-36">
        <x-tiffey::icon.cant-find />
    </div>
    <div class="text-left p-2 my-auto">
        <div class="text-xl"> These aren't the results you're looking for...</div>
        <div class="mb-4">We didn't find any results. Try clearing the filters.</div>
        <x-tiffey::button wire:click="clearFilters()">Clear filters</x-tiffey::button>
    </div>
    @else
    <div class="text-center p-2 my-auto">
        <div class="text-xl">No data</div>
        <div class="mb-4">There are no records for this table to show.</div>
    </div>
    @endif
</div>