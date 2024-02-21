<?php

namespace Permittedleader\TablesForLaravel\View\Components\Columns;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Permittedleader\TablesForLaravel\View\Components\Columns\Column;
use Permittedleader\TablesForLaravel\View\Components\Columns\Interfaces\UsesRelationships;

class BelongsTo extends Column implements UsesRelationships
{
    public bool $sortable = false;

    public $displayAttribute = 'name';

    public string $filterComponent = 'filters.belongs-to';

    public string $modelClass;

    public function __construct($key, $label = '')
    {
        parent::__construct($key, $label);
        $this->formatDisplay(function ($value) {
            if (is_object($value)) {
                return $value->{$this->displayAttribute};
            } else {
                return '';
            }
        });
    }

    /**
     * Set which attribute is used for displaying this model
     *
     * @param  string  $attributeName
     * @return void
     */
    public function displayAttribute($attributeName)
    {
        $this->displayAttribute = $attributeName;

        return $this;
    }

    /**
     * Return corresponding database field name
     */
    public function dbField(): string
    {
        if (isset($this->dbField)) {
            return $this->dbField;
        } else {
            return $this->key.'_'.(new ($this->modelClass))->getKeyName();
        }
    }

    public function query($query, $value): Builder
    {
        return $query->whereIn((new ($this->modelClass))->getTable().'.'.(new ($this->modelClass))->getKeyName(), array_keys($value, true));
    }

    /**
     * Model instances for the filter component
     */
    public function models(): array|Collection
    {
        return $this->modelClass::orderBy($this->displayAttribute)->get();
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
