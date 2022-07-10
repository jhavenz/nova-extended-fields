<?php

namespace Jhavenz\NovaExtendedFields;

use Laravel\Nova\Resource;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class NovaExtendedFieldsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('nova-extended-fields');
    }
}
