<?php

namespace Jhavenz\NovaExtendedFields;

use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;
use Jhavenz\NovaExtendedFields\Internet\Email;
use Jhavenz\NovaExtendedFields\Named\FullName;
use Laravel\Nova\Fields\Field;

class NovaExtendedFields
{
    private const CONFIG_PATH = 'nova-extended-fields';
    private const EXTENDED_FIELDS = [
        Email::class,
        FullName::class,
    ];

    public static function registerBindings(): void
    {
        foreach (self::EXTENDED_FIELDS as $classString) {
            app()->bind($classString, function (Application $app, array $args = []) use ($classString) {
                $configAttributes = self::configuredField($classString);
                $behaviors = Arr::pull($configAttributes, 'behaviors', []);

                /** @var Field $classString */
                return tap(
                    $classString::make(
                        $args['name'] ?? $configAttributes['name'] ?? null,
                        $args['attribute'] ?? $configAttributes['attribute'] ?? null,
                        $args['resolveCallback'] ?? $configAttributes['resolveCallback'] ?? null
                    ),
                    fn($field) => self::applyBehaviors($behaviors, $field)
                );
            });
        }
    }

    public static function configuredFieldPath(string $class): mixed
    {
        return sprintf("%s.fields.%s", self::CONFIG_PATH, $class);
    }

    public static function configuredField(string $class): mixed
    {
        return config(self::configuredFieldPath($class));
    }

    protected static function applyBehaviors(array $behaviors, ?Field $field = null): void
    {
        if (!count($behaviors) || !$field) {
            return;
        }

        $field->sortable = Arr::pull($behaviors, 'sortable', false);
        $field->rules = Arr::pull($behaviors, 'rules', []);
        $field->creationRules = Arr::pull($behaviors, 'creationRules', []);
        $field->updateRules = Arr::pull($behaviors, 'updateRules', []);
    }
}
