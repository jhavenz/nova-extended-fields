<?php

namespace Jhavenz\NovaExtendedFields;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Jhavenz\NovaExtendedFields\Internet\Email;
use Jhavenz\NovaExtendedFields\Named\FullName;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Text;
use PhpClass\PhpClass;
use Symfony\Component\Finder\Finder;

use function Jhavenz\rescueQuietly;

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

    public static function getNamespacedNovaField(string $targetField): string
    {
        static $fields = [];

        $fieldAttribute = function ($needle) use (&$fields, $targetField) {
            if (array_key_exists($needle, $fields)) {
                return $fields[$needle];
            }

            if (array_key_exists($needle = Str::studly(class_basename($needle)), $fields)) {
                return $fields[$needle];
            }

            return false;
        };

        if ($key = $fieldAttribute($targetField)) {
            return $fields[$key];
        }

        foreach (self::novaFieldsFinder() as $fieldFile) {
            $class = rescueQuietly(
                fn() => (new PhpClass($fieldFile->getRealPath()))->instantiate(),
            );

            if (empty($class) || !$class instanceof Field) {
                continue;
            }

            // cache as we go
            $fields[$class::class] = $class::class;
            $fields[Str::studly(class_basename($class))] = $class::class;

            if ($key = $fieldAttribute($targetField)) {
                return $fields[$key];
            }
        }

        // return Text as a default
        return Text::class;
    }

    protected static function novaFieldsFinder()
    {
        return Finder::create()
            ->in(self::novaFieldsDir())
            ->ignoreVCSIgnored(false)
            ->ignoreUnreadableDirs();
    }

    protected static function novaFieldsDir(): string
    {
        $ds = DIRECTORY_SEPARATOR;

        return defined('LARAVEL_START')
            ? sprintf(
                "%s%slaravel%snova%ssrc%sFields",
                app(PackageManifest::class)->vendorPath,
                $ds,
                $ds,
                $ds,
                $ds
            )
            : sprintf("%s%snova%ssrc%sFields", dirname(__DIR__), $ds, $ds, $ds);
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
