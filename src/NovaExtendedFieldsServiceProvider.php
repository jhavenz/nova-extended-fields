<?php

namespace Jhavenz\NovaExtendedFields;

use Jhavenz\NovaExtendedFields\Commands\ExtendedFieldMakeCommand;
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
        NovaExtendedFields::registerBindings();

        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            ExtendedFieldMakeCommand::class
        ]);
    }
}
