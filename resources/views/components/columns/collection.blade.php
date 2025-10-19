<div>
    @if(count($value) == 0)
        {{ __('tables::tables.columns.collection.no_items') }}
    @elseif(count($value) <= $column->displayCount)
        @php
            $return = [];
            foreach ($value as $item) {
                $return[] = $column->displayValue($item);
            }
        @endphp
        {{ implode(', ',$return) }}
    @else
        <x-tiffey::modal>
            <x-slot:button>
                <x-tiffey::button>
                {{ count($value) }} {{ __('tables::tables.columns.collection.items') }}
                </x-tiffey::button>
            </x-slot:button>
            <ul class="list-disc list-inside mr-2 md:columns-2 lg:columns-3">
                @foreach ($value as $item)
                    <li>{{ $column->displayValue($item) }}</li>
                @endforeach
            </ul>
        </x-tiffey::modal>
    @endif
</div>