<?php

namespace Jhavenz\NovaExtendedFields\Tests\fixtures;

use Jhavenz\NovaExtendedFields\Fields\Named\FullName;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

class NovaUser extends Resource
{
    public $resource = User::class;
    public array $customFields = [];

    public function fields(NovaRequest $request)
    {
        return collect([
            ...$this->customFields,
            FullName::field(),
        ])->unique(fn($f) => get_class($f))->all();
    }

    public function authorizedToImpersonate(NovaRequest $request)
    {
        return false;
    }

    public static function softDeletes()
    {
        return false;
    }

    public static function newModel()
    {
        return new User();
    }
}
