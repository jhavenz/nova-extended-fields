<?php

namespace Jhavenz\NovaExtendedFields\Fields\Traits;

trait FormatsNovaRules
{
    private function formatNovaRules(array $rules): array
    {
        return [
            $this->attribute => $rules,
        ];
    }
}
