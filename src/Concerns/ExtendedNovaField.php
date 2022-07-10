<?php

namespace Jhavenz\NovaExtendedFields\Concerns;

trait ExtendedNovaField
{
    public static function field(...$args): static
    {
        return app(static::class, ...$args);
    }

    public static function fieldWithPreview(...$args): static
    {
        return static::field(...$args)->showOnPreview();
    }
}
