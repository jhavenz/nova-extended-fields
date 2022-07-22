<?php

namespace Jhavenz\NovaExtendedFields\Fields\Internet;

use Illuminate\Support\Str;
use Jhavenz\NovaExtendedFields\Fields\Traits\ExtendedNovaField;
use Jhavenz\NovaExtendedFields\Fields\Traits\HasUniqueRule;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Http\Requests\NovaRequest;
use LogicException;

use function Jhavenz\LaravelHelpers\Helpers\novaRequest;

class ReadonlySlug extends Slug
{
    use ExtendedNovaField;
    use HasUniqueRule;

    public function __construct($name = 'Slug', $attribute = 'slug', $resolveCallback = null)
    {
        parent::__construct(
            $name,
            $attribute,
            $resolveCallback
        );

        $this->exceptOnForms();
    }

    public function from($from): static
    {
        return parent::from($from);
    }

    public function getRules(NovaRequest $request): array
    {
        return $this->formatNovaRules(
            $this->addUniqueRule([
                'sometimes',
                'nullable',
                'string',
                'min:2',
                'max:191',
            ])
        );
    }

    public function isReadonly(NovaRequest $request): bool
    {
        return true;
    }

    public function isShownOnPreview(NovaRequest $request, $resource): bool
    {
        return true;
    }

    public function isSortable(): bool
    {
        return true;
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $request->filled($from = $this->from->attribute) &&
        $request->whenFilled(
            $requestAttribute,
            fn($slug) => empty($model->getAttribute($attribute)) &&
                $model->setAttribute(
                    $attribute,
                    Str::slug($request->input($from))
                )
        );
    }

    public function jsonSerialize(): array
    {
        if (!isset($this->from)) {
            throw new LogicException(
                "[from] attribute is required for Slug fields. This field's value will be auto-created when a resource is created"
            );
        }

        if (novaRequest()->isInlineCreateRequest() ||
            novaRequest()->isUpdateOrUpdateAttachedRequest() ||
            novaRequest()->isCreateOrAttachRequest()) {
            if (!isset($this->table, $this->column)) {
                throw new LogicException(
                    'ReadonlySlug requires the [table] and [column] properties to be set for determining uniqueness'
                );
            }
        }

        return parent::jsonSerialize();
    }
}
