<?php

namespace Jhavenz\NovaExtendedFields\Fields\Traits;

use Illuminate\Support\Arr;
use Laravel\Nova\Fields\Field;

trait ExtendedNovaField
{
    use FormatsNovaRules;

    public static function exceptForms(...$args): static
    {
        return static::field(...$args)->exceptOnForms();
    }

    public static function field(...$args): static
    {
        if (!is_a(static::class, Field::class, true)) {
            throw new \LogicException(__TRAIT__." can only be used with Nova fields");
        }

        if (count($args) === 1 && Arr::hasAny($args[0], [
                'name',
                'attribute',
                'resolveCallback',
                'disk',
                'storageCallback',
                'resource',
                'lines',
            ])) {
            $args = $args[0];
        }

        return static::make(...$args);
    }

    public static function fieldWithPreview(...$args): static
    {
        return static::field(...$args)->showOnPreview();
    }

    public static function formsOnly(...$args): static
    {
        return static::field(...$args)->onlyOnForms();
    }

    /** The model attribute that this field represents */
    public function setTargetAttribute(string $attribute): static
    {
        $this->attribute = $attribute;

        return $this;
    }

    /** The model attribute that this field represents */
    public function setResolver(callable $resolver): static
    {
        $this->attribute = $attribute;

        return $this;
    }
}
