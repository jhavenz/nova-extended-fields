<?php

namespace Jhavenz\NovaExtendedFields;

use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;
use Jhavenz\NovaExtendedFields\Contactable\Email;
use Jhavenz\NovaExtendedFields\Contactable\FullName;
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
                $configurables = Arr::pull($configAttributes, 'configurables', []);

                /** @var Field $classString */
                return tap(
                    $classString::make(
                        $args['name'] ?? $configAttributes['name'] ?? null,
                        $args['attribute'] ?? $configAttributes['attribute'] ?? null,
                        $args['resolveCallback'] ?? $configAttributes['resolveCallback'] ?? null
                    ),
                    fn($field) => $this->applyConfigurables($configurables, $field)
                );
            });
        }
    }

    protected static function configuredField(string $class): mixed
    {
        $path = sprintf("%s.fields.%s", self::CONFIG_PATH, $class);

        return config($path);
    }

    protected function applyConfigurables(array $configurables, ?Field $field = null): void
    {
        if (!count($configurables) || !$field) {
            return;
        }

        $field->sortable = Arr::pull($configurables, 'sortable', false);
        $field->rules = Arr::pull($configurables, 'rules', []);
        $field->creationRules = Arr::pull($configurables, 'creationRules', []);
        $field->updateRules = Arr::pull($configurables, 'updateRules', []);
    }
}
