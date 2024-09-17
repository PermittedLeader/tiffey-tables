<?php

namespace Permittedleader\Tables\Traits;

trait DisplayString
{
    /**
     * Returns a translation string for this enum if set, else return backed value;
     */
    public function display(): string
    {
        $translationString = [
            'enums',
            (new \ReflectionEnum($this))->getShortName(),
            $this->name,
        ];
        $translationString = implode('.', $translationString);

        return trans()->hasForLocale($translationString) ? __($translationString) : $this->value;
    }
}
