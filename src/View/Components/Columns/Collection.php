<?php

namespace Permittedleader\Tables\View\Components\Columns;

use Permittedleader\Tables\View\Components\Columns\Column;

class Collection extends Column
{
    public bool $sortable = false;

    public bool $filterable = false;

    public string $component = 'columns.collection';

    public $displayAttribute = 'name';

    public int $displayCount = 3;

    public function __construct($key, $label = '')
    {
        $this->formatDisplay(function ($value) {
            if (is_object($value)) {
                return $value->{$this->displayAttribute};
            } else {
                return '';
            }
        });
        parent::__construct($key, $label);
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
}
