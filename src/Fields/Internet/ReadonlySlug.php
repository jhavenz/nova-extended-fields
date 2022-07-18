<?php

namespace Jhavenz\NovaExtendedFields\Fields\Internet;

use Illuminate\Support\Str;
use Jhavenz\NovaExtendedFields\Enums\IRoles;
use Jhavenz\NovaExtendedFields\Fields\Traits\ExtendedNovaField;
use Jhavenz\NovaExtendedFields\Fields\Traits\FormatsNovaRules;
use Jhavenz\NovaExtendedFields\Fields\Traits\HasUniqueRule;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Http\Requests\NovaRequest;

use function Jhavenz\LaravelHelpers\Helpers\gate;

class ReadonlySlug extends Slug
{
    use ExtendedNovaField;
    use FormatsNovaRules;
    use HasUniqueRule;

    public function __construct($name = 'Slug', $attribute = 'slug', $resolveCallback = null)
    {
        parent::__construct(
            $name,
            $attribute,
            $resolveCallback
        );
    }

    public function getCreationRules(NovaRequest $request)
    {
        return $this->formatNovaRules(
            $this->addUniqueRule([
                ...$this->isRequired($request) ? ['required'] : ['sometimes', 'nullable'],
                'string',
                'min:6',
                'max:191',
            ])
        );
    }

    public function isReadonly(NovaRequest $request): bool
    {
        if ($request->isInlineCreateRequest() || $request->isCreateOrAttachRequest()) {
            return gate()->denies('create', app(IRoles::class));
        }

        return true;
    }

    public function isSortable(): bool
    {
        return true;
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $request->whenFilled(
            $requestAttribute,
            fn($slug) => empty($model->getAttribute($attribute)) && $model->setAttribute($attribute, Str::slug($slug))
        );
    }
}
