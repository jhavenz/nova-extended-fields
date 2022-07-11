<?php

namespace Jhavenz\NovaExtendedFields\Support;

use Illuminate\Foundation\PackageManifest;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Text;
use Symfony\Component\Finder\Finder;

use function Jhavenz\rescueQuietly;

class NovaFieldFinder
{
    private static array $fields = [];

    public function namespacedNovaField(string $targetField): string
    {
        if ($key = $this->fieldsSearch($targetField)) {
            return self::$fields[$key];
        }

        foreach (self::novaFieldsFinder() as $fieldFile) {
            $phpClass = new PhpClass($fieldFile->getRealPath());

            $class = rescueQuietly(
                fn() => $phpClass->instantiate(),
                fn() => rescueQuietly(
                    fn() => $phpClass->fqcn(),
                    fn() => $fieldFile->getFilename()
                )
            );

            if (is_string($class)) {
                self::$fields[strtolower(class_basename($class))] = $class;
                file_exists($class) && self::$fields[basename($class, 'php')] = basename($class, 'php');
            }

            if ($class instanceof Field) {
                self::$fields[strtolower(class_basename($class))] = $class::class;
                self::$fields[$class::class] = $class::class;
            }

            if ($key = $this->fieldsSearch($targetField)) {
                return self::$fields[$key];
            }
        }

        // return Text as a default
        return Text::class;
    }

    protected static function novaFieldsFinder()
    {
        return Finder::create()
            ->files()
            ->in(self::novaFieldsDir())
            ->ignoreVCSIgnored(false)
            ->ignoreUnreadableDirs();
    }

    protected static function novaFieldsDir(): string
    {
        $ds = DIRECTORY_SEPARATOR;

        return defined('LARAVEL_START')
            ? sprintf(
                '%s%slaravel%snova%ssrc%sFields',
                app(PackageManifest::class)->vendorPath,
                $ds,
                $ds,
                $ds,
                $ds
            )
            : sprintf('%s%snova%ssrc%sFields', dirname(__DIR__), $ds, $ds, $ds);
    }

    /**
     * @param string $key
     *
     * @return string|false
     */
    private function fieldsSearch(string $key): string|false
    {
        if (array_key_exists($key, self::$fields)) {
            return $key;
        }

        if (array_key_exists($two = basename($key, 'php'), self::$fields)) {
            return $two;
        }

        if (array_key_exists($key = strtolower(class_basename($key)), self::$fields)) {
            return $key;
        }

        return false;
    }
}
