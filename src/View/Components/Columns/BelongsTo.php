<?php

namespace Permittedleader\Tables\View\Components\Columns;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Permittedleader\Tables\View\Components\Columns\Column;
use Permittedleader\Tables\View\Components\Columns\Interfaces\UsesRelationships;

class BelongsTo extends Column implements UsesRelationships
{
    public bool $sortable = false;

    public $displayAttribute = 'name';

    public string $filterComponent = 'filters.belongs-to';

    public string $modelClass;

    public Collection|Closure $options;

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
        $this->filterQuery(function ($query, $value) {
            return $query->whereIn((new ($this->modelClass))->getTable().'.'.(new ($this->modelClass))->getKeyName(), array_keys($value, true));
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

    /**
     * Model instances for the filter component
     */
    public function models(): array|Collection
    {
        if (isset($this->options) && $this->options instanceof Closure) {
            return ($this->options)($this->displayAttribute);
        } elseif (isset($this->options)) {
            return $this->options;
        }

        return $this->modelClass::orderBy($this->displayAttribute)->get();
    }

    public function options(Collection|Closure $options): self
    {
        $this->options = $options;

        return $this;
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
