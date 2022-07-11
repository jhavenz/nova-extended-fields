<?php

use Illuminate\Foundation\PackageManifest;
use Jhavenz\NovaExtendedFields\Named\FullName;
use Jhavenz\NovaExtendedFields\NovaExtendedFields as NEF;
use Jhavenz\NovaExtendedFields\Tests\fixtures\User;

it('has default name and attributes', function () {
    expect(FullName::field())
        ->name->toBe('Full Name')
        ->attribute->toBe('full_name');
});

it('can have custom name and attributes using the config params', function () {
    expect(FullName::field())
        ->name->toBe('Full Name')
        ->attribute->toBe('full_name');

    config([
        NEF::configuredFieldPath(FullName::class) => [
            'name' => 'Name',
            'attribute' => 'fName',
        ],
    ]);

    expect(FullName::field())
        ->name->toBe('Name')
        ->attribute->toBe('fName');
});

it('can have custom name and attributes using field params', function () {
    /** @noinspection MultipleExpectChainableInspection */
    expect(FullName::field())
        ->name->toBe('Full Name')
        ->attribute->toBe('full_name');

    /** @noinspection MultipleExpectChainableInspection */
    expect(FullName::field(['name' => 'Name', 'attribute' => 'fName',]))
        ->name->toBe('Name')
        ->attribute->toBe('fName');
});

it('can have a custom resolver using config params', closure: function () {
    expect(FullName::field())->resolveCallback->toBeNull();

    config([
        NEF::configuredFieldPath(FullName::class) => ['resolveCallback' => fn() => 'Foo Bar'],
    ]);

    $field = FullName::field();
    $field->resolve(User::factory()->create());

    expect($field->value)->toBe('Foo Bar');
});

it('can have a custom resolver using field params', closure: function () {
    dd(app(PackageManifest::class)->vendorPath.'/laravel/');
    expect(FullName::field())->resolveCallback->toBeNull();

    $field = FullName::field([
        'resolveCallback' => fn() => 'Foo Bar',
    ]);

    $field->resolve(User::factory()->create());

    expect($field->value)->toBe('Foo Bar');
});
