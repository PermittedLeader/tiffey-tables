<?php

namespace Permittedleader\Tables\View\Components\Columns;

class BelongsToMany extends BelongsTo
{
    public string $filterComponent = 'filters.belongs-to';

    public function __construct($key, $label = '')
    {
        parent::__construct($key, $label);
        $this->formatDisplay(function ($value) {
            return $value->implode($this->displayAttribute, ', ');
        });
    }

    /**
     * Return corresponding database field name
     */
    public function dbField(): string
    {
        if (isset($this->dbField)) {
            return $this->dbField;
        } else {
            return (new ($this->modelClass))->getTable().'.'.(new ($this->modelClass))->getKeyName();
        }
    }
}
