<?php

namespace Permittedleader\TablesForLaravel\View\Components\Columns;

use Illuminate\Support\Collection;
use Permittedleader\Forms\Traits\Enums\DisplayString;

class Enum extends Column
{
    public bool $sortable = false;

    public string $filterComponent = 'filters.enum';

    public string $modelClass;

    public function __construct($key, $label = '')
    {
        parent::__construct($key, $label);
        $this->formatDisplay(function ($value) {
            if (class_implements($value, DisplayString::class)) {
                return $value->display();
            } else {
                return $value;
            }
        });

        $this->filterQuery(function ($query, $value) {

            return $query->whereIn($this->key, array_keys(array_filter($value, fn ($value) => $value)));
        });
    }

    /**
     * Model instances for the filter component
     */
    public function cases(): array|Collection
    {
        return ($this->modelClass)::cases();
    }

    /**
     * Set the model class name to be used
     *
     * @param  string  $model
     * @return static
     */
    public function model($model)
    {
        $this->modelClass = $model;

        return $this;
    }
}
