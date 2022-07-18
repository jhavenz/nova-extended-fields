<?php

namespace Jhavenz\NovaExtendedFields\Fields\Named;

use Illuminate\Support\Str;
use Jhavenz\NovaExtendedFields\Fields\Traits\ExtendedNovaField;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class DisplayName extends Text
{
    use ExtendedNovaField;

    public function __construct($name = 'Display Name', $attribute = 'display_name', $resolveCallback = null)
    {
        parent::__construct(
            $name,
            $attribute,
            $resolveCallback
        );
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $request->whenFilled(
            $requestAttribute,
            fn($displayName) => $model->setAttribute($attribute, Str::headline($displayName))
        );
    }

    public function getRules(NovaRequest $request)
    {
        return ['required', 'string', 'min:6', 'max:50'];
    }

    public function getUpdateRules(NovaRequest $request)
    {
        return ['sometimes', 'nullable', 'string', 'min:6', 'max:50', 'email'];
    }

    public function isSortable(): bool
    {
        return true;
    }
}
