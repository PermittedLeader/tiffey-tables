<?php

namespace Permittedleader\Tables\View\Components\Columns;

use Closure;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Column extends Component
{
    public string $component = 'columns.column';

    public string $filterComponent = 'filters.filter';

    public string $key;

    public string $dbField;

    public string $label;

    public bool $sortable = false;

    public bool $filterable = false;

    public Closure $displayTransformation;

    public Closure $exportTransformation;

    public Closure $filterQuery;

    public bool $showOnExport = true;

    public bool $showOnView = true;

    public function __construct($key, $label = '')
    {
        $this->key = $key;
        if ($label !== '') {
            $this->label = $label;
        } else {
            $this->label = Str::of($key)->snake()->replace('_', ' ')->title();
        }
    }

    /**
     * Make the column.
     *
     * @param  string  $key    Name of the column or relationship
     * @param  string  $label  Displayed text for this columns
     * @return static
     */
    public static function make($key, $label = '')
    {
        return new static($key,$label);
    }

    /**
     * Return corresponding database field name
     *
     * @return string
     */
    public function dbField()
    {
        if (isset($this->dbField)) {
            return $this->dbField;
        } else {
            return $this->key;
        }
    }

    /**
     * Set the database column to use for sorting if different from the key
     *
     * @param  string  $columnName
     */
    public function databaseColumn($columnName): static
    {
        $this->dbField = $columnName;

        return $this;
    }

    /**
     * Return the transformed value of the column, ready for the component to render
     *
     * @return string
     */
    public function displayValue($value)
    {
        if (isset($this->displayTransformation)) {
            return ($this->displayTransformation)($value);
        } else {
            return $value;
        }
    }

    /**
     * Define a closure to process the value being tranformed for display
     *
     * @param  Closure  $displayTransformation function($value){ do stuff }
     */
    public function formatDisplay(Closure $displayTransformation): static
    {
        $this->displayTransformation = $displayTransformation;

        return $this;
    }

    /**
     * Return the transformed value of the column, ready for the export
     *
     * @param [type] $value
     * @return void
     */
    public function exportValue($value)
    {
        if (isset($this->exportTransformation)) {
            return ($this->exportTransformation)($value);
        } else {
            return $this->displayValue($value);
        }
    }

    /**
     * Define a closure to process the value being tranformed for export
     *
     * @param  Closure  $exportTransformation function($value){ do stuff }
     */
    public function formatExport(Closure $exportTransformation): static
    {
        $this->exportTransformation = $exportTransformation;

        return $this;
    }

    /**
     * Set if this column is sortable
     *
     * @param  bool  $sortable
     * @return static
     */
    public function sortable($sortable = true)
    {
        $this->sortable = $sortable;

        return $this;
    }

    /**
     * Set if this column is filterable
     *
     * @param  bool  $filterable
     * @return static
     */
    public function filterable($filterable = true)
    {
        $this->filterable = $filterable;

        return $this;
    }

    /**
     * Select which component is being used for render
     *
     * @param  string  $component
     * @return Column
     */
    public function component($component)
    {
        $this->component = 'columns.'.$component;

        return $this;
    }

    /**
     * Select which component is being used for rendering the filter
     *
     * @param  string  $component
     * @return Column
     */
    public function filterComponent($component)
    {
        $this->filterComponent = 'filters.'.$component;

        return $this;
    }

    public function visibleOnShow(bool $show = true)
    {
        $this->showOnView = $show;

        return $this;
    }

    public function visibleOnExport(bool $show = true)
    {
        $this->showOnExport = $show;

        return $this;
    }

    /**
     * The query constraints this filter adds
     *
     * @param  Builder  $query
     * @param  string|array  $value
     */
    public function query($query, $value): Builder
    {
        if (isset($this->filterQuery)) {
            return ($this->filterQuery)($query, $value);
        } else {
            return $query->where($this->key, $value);
        }
    }

    /**
     * Define a closure to process the value being tranformed for display
     *
     * @param  Closure  $displayTransformation function($query,$value){ do stuff }
     */
    public function filterQuery(Closure $filterQuery): static
    {
        $this->filterQuery = $filterQuery;

        return $this;
    }

    /**
     * Get the view / contents that represent the filter.
     */
    public function renderFilter(): View|Closure|string
    {
        return view('tables::components.'.$this->filterComponent, ['column' => $this]);
    }

    /**
     * Get the view / contents that represent the filter, and pass in data for the view to render
     */
    public function renderColumn($data): View|Closure|string
    {
        return view('tables::components.'.$this->component, ['column' => $this, 'value' => $data]);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('tables::components.'.$this->component, ['column', $this]);
    }

    // Define actions to be added when this column is present
    public function actions(): array
    {
        return [];
    }
}
