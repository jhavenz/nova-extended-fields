<?php

namespace Jhavenz\NovaExtendedFields\Support;

use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Text;
use PhpClass\PhpClass;
use Symfony\Component\Finder\Finder;

use function Jhavenz\rescueQuietly;

class NovaFieldFinder
{
    private static array $fields = [];

    public function getNamespacedNovaField(string $targetField): string
    {
        if ($key = $this->fieldsSearch($targetField)) {
            return self::$fields[$key];
        }

        foreach (self::novaFieldsFinder() as $fieldFile) {
            $class = rescueQuietly(
                fn() => (new PhpClass($fieldFile->getRealPath()))->instantiate(),
            );

            if (empty($class) || !$class instanceof Field) {
                continue;
            }

            // cache as we go
            self::$fields[$class::class] = $class::class;
            self::$fields[Str::studly(class_basename($class))] = $class::class;

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
            return self::$fields[$key];
        }

        if (array_key_exists($key = Str::studly(class_basename($key)), self::$fields)) {
            return self::$fields[$key];
        }

        return false;
    }
}
