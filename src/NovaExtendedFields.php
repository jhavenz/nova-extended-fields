<?php

namespace Jhavenz\NovaExtendedFields;

use Jhavenz\NovaExtendedFields\Shared\FullName;

class NovaExtendedFields
{
    private const CONFIG_PATH = 'nova-extended-fields';

    public function fullName(): mixed
    {
        return $this->configuredField(FullName::class);
    }

    protected function configuredField(string $class): mixed
    {
        $path = sprintf("%s.fields.%s", self::CONFIG_PATH, $class);

        return config($path);
    }
}
