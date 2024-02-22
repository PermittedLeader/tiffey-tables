<?php

namespace Permittedleader\TablesForLaravel\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Permittedleader\FlashMessages\FlashMessages;
use Permittedleader\TablesForLaravel\View\Components\Columns\Column;
use Permittedleader\TablesForLaravel\View\Components\Columns\Interfaces\UsesRelationships;

abstract class Table extends Component implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;
    use WithPagination;
    use FlashMessages;

    public bool $isSearchable = false;

    public bool $isExportable = false;

    public bool $isFilterable = true;

    public int $perPage = 10;

    public $page = 1;

    public $paginatorName = 'page';

    public string $sortBy = '';

    public string $sortDirection = 'asc';

    public string $searchString = '';

    public array $appliedFilters = [];

    public array $scope;

    public bool $detailOnly = false;

    public string $exportName = 'table-export';

    public function render()
    {
        return view('tables::livewire.table');
    }

    public function placeholder()
    {
        return view('tables::components.loading');
    }

    /**
     * Query instance for this table
     */
    abstract public function query(): Builder;

    /**
     * Columns in the table
     *
     * @return array<Column> Array of columns to display
     */
    abstract public function columns(): array;

    /**
     * Actions for each row in the table
     *
     * @return array<Action> Array of actions for each row
     */
    abstract public function actions(): array;

    public function tableActions(): array
    {
        return [];
    }

    /**
     * Modals for this table.
     */
    public function modals(): array
    {
        return [];
    }

    /**
     * Return only filterable columns
     */
    public function filterableColumns(): array
    {
        return array_filter($this->columns(), function (Column $column) {
            return $column->filterable;
        });
    }

    /**
     * Return only visible columns
     */
    public function visibleColumns(): array
    {
        return array_filter($this->columns(), function (Column $column) {
            return $column->showOnView;
        });
    }

    /**
     * Return only exportable columns
     */
    public function exportableColumns(): array
    {
        return array_filter($this->columns(), function (Column $column) {
            return $column->showOnExport;
        });
    }

    /**
     * Fetch data for this table and paginate
     *
     * @return Builder
     */
    public function data()
    {
        return $this
            ->query()
            ->when(! empty($this->scope), function ($query) {
                if ($this->scope['type'] == 'relation') {
                    $query->whereHas($this->scope['related'], function ($query) {
                        $query->where(isset($this->scope['key']) ? $this->scope['key'] : 'id', $this->scope['value']);
                    });
                }
            })
            ->when($this->columns(), function ($query) {
                foreach ($this->columns() as $column) {
                    if (! empty($this->appliedFilters[$column->key]) && ($column instanceof UsesRelationships)) {
                        $query->whereHas($column->key, function ($query) use ($column) {
                            $column->query($query, $this->appliedFilters[$column->key]);
                        });
                    } elseif ($column instanceof UsesRelationships) {
                        $query->with($column->key);
                    }
                }
            })
            ->when($this->sortBy !== '', function ($query) {
                $query->orderBy($this->sortBy, $this->sortDirection);
            })
            ->when($this->searchString !== '', function ($query) {
                $query->searchWithFiltering($this->searchString);
            })
            ->when(! empty($this->appliedFilters), function ($query) {
                foreach ($this->filterableColumns() as $filter) {
                    if (! empty($this->appliedFilters[$filter->key]) && ! ($filter instanceof UsesRelationships)) {
                        $filter->query($query, $this->appliedFilters[$filter->key]);
                    }
                }
            })
            ->paginate($this->perPage, ['*'], $this->paginatorName);
    }

    /**
     * Apply sort direction and column
     *
     * @param  string  $key   Key of the column used for sorting
     * @return void
     */
    public function sort($key)
    {
        $this->resetPage();

        if ($this->sortBy === $key) {
            $direction = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            $this->sortDirection = $direction;

            return;
        }

        $this->sortBy = $key;
        $this->sortDirection = 'asc';
    }

    /**
     * Apply search criteria
     *
     * @return void
     */
    public function search()
    {
        $this->resetPage();
    }

    /**
     * Apply filters
     *
     * @return void
     */
    public function applyFilters()
    {
        $this->resetPage();
    }

    /**
     * clear filters
     *
     * @return void
     */
    public function clearFilters($key = '')
    {
        if ($key == '') {
            $this->appliedFilters = [];
        } else {
            unset($this->appliedFilters[$key]);
        }

        $this->resetPage();
    }

    /**
     * Map rows ready for export
     *
     * @param  Row  $row
     */
    public function map($row): array
    {
        $rows = [];
        foreach ($this->exportableColumns() as $column) {
            $rows[] = $column->exportValue($row->{$column->key});
        }

        return $rows;
    }

    public function headings(): array
    {
        $headings = [];
        foreach ($this->exportableColumns() as $column) {
            $headings[] = $column->label;
        }

        return $headings;
    }

    /**
     * Download the prepared excel document.
     *
     * @return Excel::download
     */
    public function export()
    {
        if ($this->isExportable) {
            return Excel::download($this, $this->exportName.'-'.Carbon::now()->format('Y-m-d\THis').'-'.auth()->user()->name.'.xlsx');
        } else {
            return false;
        }
    }
}
