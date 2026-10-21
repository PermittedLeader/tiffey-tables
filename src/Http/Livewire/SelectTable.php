<?php

namespace Permittedleader\Tables\Http\Livewire;

use Livewire\WithoutUrlPagination;
use Permittedleader\Tables\View\Components\Actions\Action;
use Permittedleader\Tables\View\Components\Columns\Column;

class SelectTable extends Table
{
    use WithoutUrlPagination;

    public bool $isSearchable = true;

    public bool $isExportable = false;

    public bool $isFilterable = true;

    public bool $multiple = false;

    public int $selectionLimit = 1;

    public string $messageBag = 'select';

    public array $selectedDisplay = [];

    public string $model;
    public string $displayAttributeLabel = 'Name';
    public string $displayAttribute = 'name';

    public function query(): \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation
    {
        return (new $this->model)::query();
    }

    public function columns(): array
    {
        return [
            Column::make('id','ID'),
            Column::make($this->displayAttribute,$this->displayAttributeLabel)
        ];
    }

    public function actions(): array
    {
        if($this->multiple == false)
        {
            $this->selectable = false;
            return [
                Action::makeAction(function ($data) {
                    return 'select('.$data->id.', "'.$data->{$this->displayAttribute}.'")';
                }, __('tables::tables.select.select'))->showLabel()->icon('fa-solid fa-hand-pointer'),
            ];
        } else {
            $this->selectable = true;
            return [];
        }  
    }

    public function bulkActions(): array
    {
        if($this->multiple == false)
        {
            return [];
        } else {
            return [
                Action::makeAction('selectMulti()', __('tables::tables.select.select'))->showLabel()->icon('fa-solid fa-hand-pointer'),
            ];
        }  
    }

    public function select(int $modelKey, string $display)
    {
        $this->selectedIds = [$modelKey];
        $this->selectedDisplay = [$display];
        $this->forwardSelected();
    }

    public function selectMulti()
    {
        $this->selectedDisplay = (new $this->model)::whereIn('id', $this->selectedIds)->pluck($this->displayAttribute)->toArray();
        $this->forwardSelected();
    }

    public function forwardSelected()
    {
        $this->dispatch('selected', [$this->selectedIds, $this->selectedDisplay]);
    }
}
