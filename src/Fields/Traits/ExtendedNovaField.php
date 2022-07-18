<?php

namespace Jhavenz\NovaExtendedFields\Fields\Traits;

use Illuminate\Support\Arr;
use Laravel\Nova\Fields\Field;
use Nette\Utils\Arrays;

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

        $constructor = new \ReflectionMethod(static::class, '__construct');

        foreach ($constructor->getParameters() as $param) {
            if (count($args) === 1 && Arr::has(Arr::first($args), $param->getName())) {
                $params = Arrays::invokeMethod($constructor->getParameters(), 'getName');
                $args = array_filter($args[0], fn($arg, $key) => in_array($key, $params), ARRAY_FILTER_USE_BOTH);
                break;
            }
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
