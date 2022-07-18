<?php

namespace Jhavenz\NovaExtendedFields\Fields\Named;

use Jhavenz\NovaExtendedFields\Fields\Traits\ExtendedNovaField;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class FullName extends Text
{
    use ExtendedNovaField;

    public function __construct($name = 'Full Name', $attribute = 'fullname', ?callable $resolveCallback = null)
    {
        parent::__construct(
            $name,
            $attribute,
            $resolveCallback
        );
    }

    public function isSortable(): bool
    {
        return true;
    }

    public function getRules(NovaRequest $request): array
    {
        return $this->formatNovaRules([
            ...$this->isRequired($request) ? ['required'] : ['sometimes', 'nullable'],
            'string',
            'min:5',
            'max:50',
            function ($attr, $value, $fail) {
                if (!(filled($value) && is_string($value))) {
                    return;
                }

                preg_match('#\s#', $value, $whitespaceMatches);

                if (empty($whitespaceMatches)) {
                    $fail('Full name must contain a first and last name with a space in between');
                }
            },
        ]);
    }
}
