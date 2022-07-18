<?php

declare(strict_types=1);

namespace Jhavenz\NovaExtendedFields\Fields\Database;

use Jhavenz\NovaExtendedFields\Fields\Traits\ExtendedNovaField;
use Jhavenz\NovaExtendedFields\Fields\Traits\FormatsNovaRules;
use Jhavenz\NovaExtendedFields\Fields\Traits\HasUniqueRule;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;

class NumericPrimaryKey extends ID
{
    use ExtendedNovaField;
    use FormatsNovaRules;
    use HasUniqueRule;

    public function __construct($name = 'ID', $attribute = 'id', callable $resolveCallback = null)
    {
        parent::__construct(
            $name,
            $attribute,
            $resolveCallback
        );
    }

    public function getRules(NovaRequest $request): array
    {
        return $this->formatNovaRules(
            $this->addUniqueRule([
                'sometimes',
                'nullable',
                'numeric',
            ])
        );
    }

    /**
     * @return bool
     */
    public function isSortable(): bool
    {
        return true;
    }
}
