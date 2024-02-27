<?php

namespace Permittedleader\TablesForLaravel\Http\Livewire;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Permittedleader\TablesForLaravel\Http\Livewire\Table;
use Permittedleader\TablesForLaravel\View\Components\Actions\Action;

abstract class BelongsToManyTable extends Table
{
    protected $listeners = [];
    
    public bool $isSearchable = false;

    public bool $isExportable = false;

    public bool $isFilterable = false;

    public string $relationshipName;

    public string $messageBag = 'attach';

    public Model $model;

    public abstract function query(): Builder;

    public abstract function columns(): array;

    public function actions(): array
    {
        return [
            Action::makeAction(function($data){
                return 'attach('.$data->id.')';
            },'Attach')->showLabel()->gate(function($data){
                return !$this->model->{$this->relationshipName}->contains($data);
            }),
            Action::makeAction(function($data){
                return 'detach('.$data->id.')';
            },'Detach')->showLabel()->gate(function($data){
                return $this->model->{$this->relationshipName}->contains($data);
            })
        ];
    }

    public function attach($modelKey){
        $this->model->{$this->relationshipName}()->attach($modelKey);

        self::success('You have successfully attached these items.','Attached',bag: $this->getMessageBagName());

        $this->dispatch('refreshParent');
    }

    public function detach($modelKey)
    {
        $this->model->{$this->relationshipName}()->detach($modelKey);

        self::success('You have successfully detached these items.','Detached',bag: $this->getMessageBagName());

        $this->dispatch('refreshParent');
    }
}
