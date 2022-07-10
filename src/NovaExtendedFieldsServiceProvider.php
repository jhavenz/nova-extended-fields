<?php

namespace Jhavenz\NovaExtendedFields;

use Illuminate\Foundation\Application;
use Jhavenz\NovaExtendedFields\Shared\FullName;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class NovaExtendedFieldsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('nova-extended-fields')
            ->hasConfigFile();
    }

    public function packageRegistered()
    {
        $this->app->bind(
            FullName::class,
            function (Application $app, array $args = []) {
                $fields = $app[NovaExtendedFields::class]->fullName();

                return FullName::make(
                    $args['name'] ?? $fields['name'] ?? null,
                    $args['attribute'] ?? $fields['attribute'] ?? null,
                    $args['resolveCallback'] ?? $fields['resolveCallback'] ?? null
                );
            }
        );
    }
}
