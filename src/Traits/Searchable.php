<?php

namespace Permittedleader\TablesForLaravel\Traits;

use Illuminate\Support\Facades\Cache;

trait Searchable
{

    protected $searchableFields = ['*'];

    /**
     * Search paginated items ordering by ID descending
     *
     * @param  int  $paginationQuantity
     * @return void
     */
    public function scopeSearchLatestPaginated(
        $query,
        string $search,
        $paginationQuantity = 10
    ) {
        return $query
            ->search($search)
            ->orderBy('updated_at', 'desc')
            ->paginate($paginationQuantity);
    }

    /**
     * Adds a scope to search the table based on the
     * $searchableFields array inside the model
     *
     * @param [type] $query
     * @param [type] $search
     * @return void
     */
    public function scopeSearch($query, $search)
    {
        $query->where(function ($query) use ($search) {
            foreach ($this->getSearchableFields() as $field) {
                if (! in_array($field, array_diff($this->excludeFieldsFromSearch(), $this->getSearchableFields()))) {
                    $query->orWhere($this->getTable().'.'.$field, 'like', "%{$search}%");
                }
            }
        });

        return $query;
    }

    public function scopeSearchWithFiltering($query, $search)
    {
        $search_items = explode('&&', $search);
        foreach ($search_items as $search_item) {
            $search_item = explode('::', $search_item);
            if (count($search_item) > 1) {
                $query->where(function ($query) use ($search_item) {
                    if (! in_array($search_item[0], array_diff($this->excludeFieldsFromSearch(), $this->getSearchableFields()))) {
                        $query->where($this->getTable().'.'.$search_item[0], 'like', '%'.trim($search_item[1]).'%');
                    }
                });
            } else {
                $query->where(function ($query) use ($search_item) {
                    foreach ($this->getSearchableFields() as $field) {
                        if (! in_array($field, array_diff($this->excludeFieldsFromSearch(), $this->getSearchableFields()))) {
                            $query->orWhere($this->getTable().'.'.$field, 'like', '%'.trim($search_item[0]).'%');
                        }
                    }
                });
            }
        }

        return $query;
    }

    /**
     * Returns the searchable fields. If $searchableFields is undefined,
     * or is an empty array, or its first element is '*', it will search
     * in all table fields
     *
     * @return array
     */
    protected function getSearchableFields()
    {
        return Cache::remember(get_class($this).'_searchable_fields', 86400, function () {
            if (isset($this->searchableFields) && count($this->searchableFields)) {
                return $this->searchableFields[0] === '*'
                    ? $this->getAllModelTableFields()
                    : $this->searchableFields;
            }

            return $this->getAllModelTableFields();
        });
    }

    protected function excludeFieldsFromSearch()
    {
        if (isset($this->excludeFromSearch) && count($this->excludeFromSearch)) {
            return array_merge(config('tables.search.excludedFields'), $this->excludeFromSearch);
        }

        return config('tables.search.excludedFields');
    }

    /**
     * Gets all fields from Model's table
     *
     * @return array
     */
    protected function getAllModelTableFields()
    {
        $tableName = $this->getTable();

        return $this->getConnection()
            ->getSchemaBuilder()
            ->getColumnListing($tableName);
    }
}
