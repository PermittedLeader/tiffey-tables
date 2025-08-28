<?php

namespace Permittedleader\Tables\View\Components\Columns;

class SoftDeletes extends Column
{
    public string $component = 'columns.boolean';

    public string $filterComponent = 'filters.boolean';

    public bool $filterable = true;

    public function __construct($key, $label='')
    {
        if(!$label){
            $label = __('tables::tables.columns.deleted');
        }
        parent::__construct($key, $label);

        $this->formatDisplay(function($value){
            return (bool) $value;
        });

        $this->filterQuery(function ($query, $value) {
            if($value == "false"){
                return $query->whereNull($this->key);
            } else {
                return $query->withTrashed()->whereNotNull($this->key);
            }
        });
    }

    public static  function make($key = 'deleted_at', $label = '')
    {
        return new static($key,$label);
    }
}
