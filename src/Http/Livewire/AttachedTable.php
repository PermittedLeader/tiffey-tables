<?php

namespace Permittedleader\Tables\Http\Livewire;

use Permittedleader\Tables\Http\Livewire\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Permittedleader\Tables\View\Components\Actions\Action;

abstract class AttachedTable extends Table
{
    public bool $isSearchable = true;

    public Model $model;

    public string $relationshipName;

    abstract public function query(): Builder;

    abstract public function columns(): array;

    public function actions(): array
    {
        return [
            Action::makeAction(
                function ($data) {
                    return 'detach('.$data->id.')';
                },
                __('tables::tables.relationships.detach'))
                ->showLabel()
                ->icon('fa-solid fa-link-slash'),
        ];
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
    }
}
