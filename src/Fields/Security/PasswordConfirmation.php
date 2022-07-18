<?php

namespace Jhavenz\NovaExtendedFields\Fields\Security;

use Jhavenz\NovaExtendedFields\Fields\Traits\ExtendedNovaField;
use Laravel\Nova\Fields\PasswordConfirmation as BasePasswordConfirmation;
use Laravel\Nova\Http\Requests\NovaRequest;

class PasswordConfirmation extends BasePasswordConfirmation
{
    use ExtendedNovaField;

    public function __construct(
        $name = 'Password Confirmation',
        $attribute = 'password_confirmation',
        ?callable $resolveCallback = null,
        private readonly string $passwordAttribute = 'password',
        bool $hideOnCreate = false,
        bool $hideOnUpdate = false,
        bool $hideFromIndex = true,
        bool $hideFromDetail = true,
    ) {
        parent::__construct(
            $name,
            $attribute,
            $resolveCallback
        );

        $hideOnCreate && $this->hideWhenCreating();
        $hideOnUpdate && $this->hideWhenUpdating();
        $hideFromIndex && $this->hideFromIndex();
        $hideFromDetail && $this->hideFromDetail();
    }

    protected function fillAttribute(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        // noop
    }

    public function getRules(NovaRequest $request)
    {
        return $this->formatNovaRules([
            ...$this->isRequired($request) ? ['required'] : ['sometimes', 'nullable'],
            'string',
            'same:'.$this->passwordAttribute,
        ]);
    }

    public function isRequired(NovaRequest $request)
    {
        return $request->isCreateOrAttachRequest()
            || $request->isInlineCreateRequest()
            || $request->isUpdateOrUpdateAttachedRequest();
    }
}
