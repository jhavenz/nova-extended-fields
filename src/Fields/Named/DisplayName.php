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

    public function getRules(NovaRequest $request): array
    {
        return $this->formatNovaRules(['required', 'string', 'min:6', 'max:50']);
    }

    public function getUpdateRules(NovaRequest $request): array
    {
        return $this->formatNovaRules(['sometimes', 'nullable', 'string', 'min:6', 'max:50']);
    }

    public function isSortable(): bool
    {
        return true;
    }
}
