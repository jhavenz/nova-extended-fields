<?php

namespace Jhavenz\NovaExtendedFields\Fields\Internet;

use Jhavenz\NovaExtendedFields\Fields\Traits\ExtendedNovaField;
use Jhavenz\NovaExtendedFields\Fields\Traits\HasUniqueRule;
use Laravel\Nova\Fields\Email as BaseEmail;
use Laravel\Nova\Http\Requests\NovaRequest;

class BasicEmail extends BaseEmail
{
    use ExtendedNovaField;
    use HasUniqueRule;

    public function getUpdateRules(NovaRequest $request): array
    {
        return $this->formatNovaRules(
            $this->addUniqueRule([
                'sometimes',
                'nullable',
                'string',
                'min:6',
                'max:50',
                'email',
            ])
        );
    }

    public function getRules(NovaRequest $request): array
    {
        return $this->formatNovaRules(
            $this->addUniqueRule([
                'required',
                'string',
                'min:6',
                'max:50',
            ])
        );
    }

    public function isSortable(): bool
    {
        return true;
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $request->whenFilled(
            $requestAttribute,
            fn($email) => $model->setAttribute($attribute, strtolower($email))
        );
    }
}
