<?php

namespace Jhavenz\NovaExtendedFields\Fields\Named;

use Illuminate\Support\Str;
use Jhavenz\NovaExtendedFields\Fields\Traits\ExtendedNovaField;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class FirstName extends Text
{
    use ExtendedNovaField;

    public function __construct($name = 'First Name', $attribute = 'first_name', callable $resolveCallback = null)
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
            fn($firstName) => $model->setAttribute($attribute, Str::headline($firstName))
        );
    }

    public function getRules(NovaRequest $request): array
    {
        return $this->formatNovaRules(['required', 'string', 'min:6', 'max:50']);
    }

    public function getUpdateRules(NovaRequest $request): array
    {
        return $this->formatNovaRules(['sometimes', 'nullable', 'string', 'min:6', 'max:50', 'email']);
    }

    public function isShownOnPreview(NovaRequest $request, $resource): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isSortable(): bool
    {
        return true;
    }
}
