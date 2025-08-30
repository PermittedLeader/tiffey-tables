<?php

namespace Permittedleader\Tables\View\Components\Columns;

use Permittedleader\Tables\View\Components\Actions\Action;

class SoftDeletes extends Column
{
    public string $component = 'columns.boolean';

    public string $filterComponent = 'filters.boolean';

    public bool $filterable = true;

    public function __construct($key, $label='')
    {
        if(!$label){
            $label = __('tables.columns.deleted');
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

    public function actions(): array
    {
        return [
            Action::makeAction(function($data){
                if(auth()->user()->can('restore'.$data)&&$data->trashed())
                {
                    $data->restore();
                }
            },__('tables.actions.restore'))->icon('fa-solid fa-recylce')
        ];
    }
}
