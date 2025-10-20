<?php

namespace Permittedleader\Tables\Http\Livewire;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Livewire\WithoutUrlPagination;
use Permittedleader\Tables\View\Components\Actions\Action;

abstract class BelongsToManyTable extends Table
{
    use WithoutUrlPagination;

    protected $listeners = ['detach'];

    public bool $isSearchable = true;

    public bool $isExportable = false;

    public bool $isFilterable = false;

    public string $relationshipName;

    public string $messageBag = 'attach';

    public Model $model;

    abstract public function query(): Builder;

    abstract public function columns(): array;

    public function actions(): array
    {
        return [
            Action::makeAction(function ($data) {
                return 'attach('.$data->id.')';
            }, __('tables::tables.relationships.attach'))->showLabel()->gate(function ($data) {
                return ! $this->model->{$this->relationshipName}?->contains($data);
            })->icon('fa-solid fa-link'),
            Action::makeAction(function ($data) {
                return 'detach('.$data->id.')';
            }, __('tables::tables.relationships.detach'))->showLabel()->gate(function ($data) {
                return $this->model->{$this->relationshipName}?->contains($data);
            })->icon('fa-solid fa-link-slash'),
        ];
    }

    public function attach($modelKey)
    {
        $relationship = $this->model->{$this->relationshipName}();

        if ($relationship instanceof BelongsToMany) {
            $relationship->attach($modelKey);
        } elseif ($relationship instanceof HasMany) {
            $relatedModel = $relationship->getRelated()->find($modelKey);
            $relatedModel->{$relationship->getForeignKeyName()} = $this->model->id;
            $relatedModel->save();
        }

        $this->success(__('tables::tables.relationships.attach_message',['relationshipName'=>$this->relationshipName, 'id'=>$modelKey]), __('tables::tables.relationships.attached'));

        $this->dispatch('refreshParent');
    }

    public function detach($modelKey)
    {
        $relationship = $this->model->{$this->relationshipName}();

        if ($relationship instanceof BelongsToMany) {
            $relationship->detach($modelKey);
        } elseif ($relationship instanceof HasMany) {
            $relatedModel = $relationship->getRelated()->find($modelKey);
            $relatedModel->{$relationship->getForeignKeyName()} = null;
            $relatedModel->save();
        }

        $this->success(__('tables::tables.relationships.detach_message',['relationshipName'=>$this->relationshipName, 'id'=>$modelKey]), __('tables::tables.relationships.detached'));

        $this->dispatch('refreshParent');
    }
}
