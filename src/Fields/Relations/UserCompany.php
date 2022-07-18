<?php

namespace Jhavenz\NovaExtendedFields\Fields\Relations;

use Jhavenz\NovaExtendedFields\Fields\Traits\ExtendedNovaField;
use Laravel\Nova\Fields\BelongsTo;

class UserCompany extends BelongsTo
{
    use ExtendedNovaField;

    public function __construct($name = 'Company', $attribute = 'company', $resource = 'App\Nova\Company')
    {
        parent::__construct(
            $name,
            $attribute,
            $resource
        );
    }

    public function isSearchable($request): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isSortable(): bool
    {
        return false;
    }

    public function setResourceClass(string $resourceClass): static
    {
        $this->resourceClass = $resourceClass;

        return $this;
    }
}
