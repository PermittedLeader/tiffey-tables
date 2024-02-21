@if ($paginator->hasPages())
@php(isset($this->numberOfPaginatorsRendered[$paginator->getPageName()]) ? $this->numberOfPaginatorsRendered[$paginator->getPageName()]++ : $this->numberOfPaginatorsRendered[$paginator->getPageName()] = 1)
<div class="flex flex-col md:flex-row gap-2 justify-between"> 
    <div>
        <div class="text-sm">
            {!! __('Showing') !!}
            <span class="font-medium">{{ $paginator->firstItem() }}</span>
            {!! __('to') !!}
            <span class="font-medium">{{ $paginator->lastItem() }}</span>
            {!! __('of') !!}
            <span class="font-medium">{{ $paginator->total() }}</span>
            {!! __('results') !!}
        </div>
        <span class="hidden md:block">
            <x-tiffey::input.select label="Per page" wire:model="perPage" inBlock="true">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
            </x-tiffey::input.select>
        </span>
        
    </div>
    
    <div class="md:my-4 flex flex-row justify-between gap-1">
        <span>
            {{-- Previous Page Link --}}
            <x-tiffey::button
                wire:click="previousPage('{{ $paginator->getPageName() }}')"
                rel="prev"
            >
                Previous
            </x-tiffey::button>
        </span>
        <span class="hidden md:flex md:flex-row md:gap-1">
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span>
                        <x-tiffey::button disabled="true">
                            {{ $element }}
                        </x-tiffey::button>
                    </span>
                    
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <span wire:key="paginator-{{ $paginator->getPageName() }}-{{ $this->numberOfPaginatorsRendered[$paginator->getPageName()] }}-page{{ $page }}">
                            @if($page == $paginator->currentPage())
                                <x-tiffey::button
                                    disabled="true"
                                    color="bg-brand-500"
                                    wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                >
                                    {{ $page }}
                                </x-tiffey::button>
                            @else
                                <x-tiffey::button
                                wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                >
                                    {{ $page }}
                                </x-tiffey::button>
                            @endif
                            
                        </span>
                        
                    @endforeach
                @endif
            @endforeach

        </span>
        
        <span>
            {{-- Next Page Link --}}
            <x-tiffey::button 
                wire:click="nextPage('{{ $paginator->getPageName() }}')" 
                rel="next"
            >
                Next
            </x-tiffey::button>
            
        </span>
    </div>
    
</div>
@endif