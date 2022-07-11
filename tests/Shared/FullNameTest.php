<?php

use Jhavenz\NovaExtendedFields\Contactable\FullName;
use Jhavenz\NovaExtendedFields\Tests\fixtures\User;

it('has default name and attributes', function () {
    expect(FullName::field())
        ->name->toBe('Name')
        ->attribute->toBe('full_name');
});

it('can have custom name and attributes using the config params', function () {
    expect(FullName::field())
        ->name->toBe('Name')
        ->attribute->toBe('full_name');

    config([
        'nova-extended-fields.fields.Jhavenz\NovaExtendedFields\Shared\FullName' => [
            'name' => 'Full Name',
            'attribute' => 'fName',
        ],
    ]);

    expect(FullName::field())
        ->name->toBe('Full Name')
        ->attribute->toBe('fName');
});

it('can have custom name and attributes using field params', function () {
    /** @noinspection MultipleExpectChainableInspection */
    expect(FullName::field())
        ->name->toBe('Name')
        ->attribute->toBe('full_name');

    /** @noinspection MultipleExpectChainableInspection */
    expect(FullName::field(['name' => 'Full Name', 'attribute' => 'fName',]))
        ->name->toBe('Full Name')
        ->attribute->toBe('fName');
});

it('can have a custom resolver using config params', closure: function () {
    expect(FullName::field())->resolveCallback->toBeNull();

    config([
        'nova-extended-fields.fields.'.FullName::class => ['resolveCallback' => fn() => 'Foo Bar'],
    ]);

    $field = FullName::field();
    $field->resolve(User::factory()->create());

    expect($field->value)->toBe('Foo Bar');
});

it('can have a custom resolver using field params', closure: function () {
    expect(FullName::field())->resolveCallback->toBeNull();

    $field = FullName::field([
        'resolveCallback' => fn() => 'Foo Bar',
    ]);

    $field->resolve(User::factory()->create());

    expect($field->value)->toBe('Foo Bar');
});
