<?php

namespace Jhavenz\NovaExtendedFields\Fields\Security;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Jhavenz\LaravelHelpers\Traits\HasHashedPassword;
use Jhavenz\NovaExtendedFields\Fields\Traits\ExtendedNovaField;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Http\Requests\NovaRequest;

class UncompromisedPassword extends Password
{
    use ExtendedNovaField;
    use HasHashedPassword;

    public function __construct(
        $name = 'Password',
        $attribute = 'password',
        ?callable $resolveCallback = null,
        bool $onlyOnForms = true,
        private readonly bool $confirmed = true
    ) {
        parent::__construct(
            $name,
            $attribute,
            $resolveCallback
        );

        if ($onlyOnForms) {
            $this->onlyOnForms();
        }
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $request->whenFilled(
            $requestAttribute,
            fn($password) => $model->setAttribute(
                $attribute,
                !$this->passwordIsHashed() ? Hash::make($password) : $password
            )
        );
    }

    public function getRules($request): array
    {
        return $this->formatNovaRules([
            ...$this->isRequired($request) ? ['required'] : ['sometimes', 'nullable'],
            ...$this->confirmed ? ['confirmed'] : [],
            $this->passwordRule(),
        ]);
    }

    public function isRequired(NovaRequest $request)
    {
        if (isset($this->requiredCallback)) {
            return parent::isRequired($request);
        }

        return $request->isInlineCreateRequest() || $request->isCreateOrAttachRequest();
    }

    public function password(): ?string
    {
        return $this->value;
    }

    public function passwordAttribute(): string
    {
        return $this->attribute;
    }

    public function passwordRule(): PasswordRule
    {
        return (new PasswordRule(6))->when(
            app()->environment('production'),
            fn(PasswordRule $rule) => $rule->uncompromised()
        );
    }
}
