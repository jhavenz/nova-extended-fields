<?php

namespace Jhavenz\NovaExtendedFields\Tests;

use Jhavenz\NovaExtendedFields\NovaExtendedFieldsServiceProvider;
use Laravel\Nova\NovaApplicationServiceProvider;
use Laravel\Nova\NovaCoreServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\LaravelRay\RayServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            RayServiceProvider::class,
            NovaCoreServiceProvider::class,
            NovaApplicationServiceProvider::class,
            NovaExtendedFieldsServiceProvider::class,

        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }
}
