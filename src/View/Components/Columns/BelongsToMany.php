<?php

namespace Permittedleader\Tables\View\Components\Columns;

class BelongsToMany extends BelongsTo
{
    public string $filterComponent = 'filters.belongs-to';

    public string $component = 'columns.collection';

    public int $displayCount = 3;

    public $itemPlural = null;

    public function __construct($key, $label = '')
    {
        parent::__construct($key, $label);
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

    /**
     * Set how many items are displayed
     *
     * @param  int  Number of items to display before moving to a menu
     * @return void
     */
    public function displayCount(int $count)
    {
        $this->displayCount = $count;

        return $this;
    }

    /**
     * Set what is displayed as the name of the items
     *
     * @param  int  Text to display to describe count of items
     * @return void
     */
    public function itemText(string $text)
    {
        $this->itemPlural = $text;

        return $this;
    }
}
