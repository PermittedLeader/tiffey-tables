<?php

namespace Permittedleader\Tables\Traits;

use Illuminate\Database\Eloquent\Model;
use Permittedleader\FlashMessages\FlashMessages;
use Permittedleader\Tables\View\Components\Actions\Action;
use Permittedleader\Tables\View\Components\Columns\Column;

trait SoftDeletes
{

    public function columnsSoftDeletes(): array
    {
        return [
            Column::make('deleted_at',__('tables::tables.columns.deleted'))->filterQuery(function ($query, $value) {
                if($value == "false"){
                    return $query->whereNull('deleted_at');
                } else {
                    return $query->withTrashed()->whereNotNull('deleted_at');
                }
            })->formatDisplay(function($value){
                return (bool) $value;
            })->filterable(auth()->user()->can('restore',$this->query()->getModel()))->component('boolean')->filterComponent('boolean')->visibleOnShow(false)
        ];
    }

    public function actionsSoftDeletes(): array
    {
        return [
            Action::makeAction(function($data){
                return 'restore('.$data->id.')';
    
            },__('tables.actions.restore'))->icon('fa-solid fa-recycle')->gate(function($data){
                return method_exists($data, 'bootSoftDeletes') && $data->trashed() && auth()->user()->can('restore',$data);
            })
        ];
    }

    public function restore(int $id){
        $model = $this->query()->withTrashed()->where('id',$id)->first();

        $model->restore();
        
        $this->success(__('tables.actions.restored'));
    }
}
