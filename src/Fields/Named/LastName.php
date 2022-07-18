<?php

namespace Jhavenz\NovaExtendedFields\Fields\Named;

use Illuminate\Support\Str;
use Jhavenz\NovaExtendedFields\Fields\Traits\ExtendedNovaField;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class LastName extends Text
{
    use ExtendedNovaField;

    public function __construct($name = 'Last Name', $attribute = 'last_name', callable $resolveCallback = null)
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
            fn($lastName) => $model->setAttribute($attribute, Str::headline($lastName))
        );
    }

    public function getRules(NovaRequest $request): array
    {
        return ['required', 'min:6', 'max:50'];
    }

    public function getUpdateRules(NovaRequest $request): array
    {
        return ['sometimes', 'nullable', 'min:6', 'max:50'];
    }

    public function isShownOnPreview(NovaRequest $request, $resource): bool
    {
        return true;
    }

    public function isSortable(): bool
    {
        return true;
    }
}
