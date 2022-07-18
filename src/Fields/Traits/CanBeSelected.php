<?php

namespace Jhavenz\NovaExtendedFields\Fields\Traits;

use Laravel\Nova\Fields\Select;

trait CanBeSelected
{
    public static function forOptions(iterable $options, ...$fieldArgs): static
    {
        return tap(static::field(...$fieldArgs), function ($self) use ($options) {
            if (!$self instanceof Select) {
                throw new \LogicException(class_basename(static::class)." must be a [Select] field.");
            }

            $self->optionsCallback = collect($options);
        });
    }
}
