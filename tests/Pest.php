<?php

use Illuminate\Database\Console\Seeds\SeedCommand;
use Illuminate\Database\Console\WipeCommand;
use Illuminate\Support\Facades\Schema;
use Jhavenz\NovaExtendedFields\Tests\fixtures\User;
use Jhavenz\NovaExtendedFields\Tests\fixtures\UserSeeder;
use Jhavenz\NovaExtendedFields\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

function migrate(): void
{
    test()->artisan(WipeCommand::class);

    foreach (
        [
            User::class,
        ] as $model
    ) {
        $model::migrate();
    }
}

function seed(): void
{
    if (!count(Schema::connection('testing')->getAllTables())) {
        migrate();
    }

    test()->artisan(SeedCommand::class, ['class' => UserSeeder::class]);
}
