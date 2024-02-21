<?php

namespace Permittedleader\TablesForLaravel\View\Components\Columns\Interfaces;

interface UsesRelationships
{
    /**
     * Set the name of the attribute from the relationship to display
     *
     * @param  string  $attributeName
     * @return void
     */
    public function displayAttribute($attributeName);
}
