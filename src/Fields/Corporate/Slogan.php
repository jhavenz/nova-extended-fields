<?php

namespace Jhavenz\NovaExtendedFields\Fields\Corporate;

use Jhavenz\NovaExtendedFields\Fields\Traits\ExtendedNovaField;
use Jhavenz\NovaExtendedFields\Fields\Traits\FormatsNovaRules;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Slogan extends Text
{
    use ExtendedNovaField;
    use FormatsNovaRules;

    public function __construct($name = 'Slogan', $attribute = 'slogan', $resolveCallback = null)
    {
        parent::__construct(
            $name,
            $attribute,
            $resolveCallback
        );
    }

    public function getRules(NovaRequest $request)
    {
        return $this->formatNovaRules([
            'sometimes',
            'nullable',
            'string',
            'max:100',
        ]);
    }

    public function isSortable(): bool
    {
        return false;
    }
}
