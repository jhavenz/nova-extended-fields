<?php

use Jhavenz\NovaExtendedFields\Fields\Named\FullName;
use Jhavenz\NovaExtendedFields\Tests\fixtures\User;

it('has default name and attributes', function () {
    expect(FullName::field())
        ->name->toBe('Full Name')
        ->attribute->toBe('fullname');
});

it('can have custom name and attributes using field params', function () {
    /** @noinspection MultipleExpectChainableInspection */
    expect(FullName::field())
        ->name->toBe('Full Name')
        ->attribute->toBe('fullname');

    /** @noinspection MultipleExpectChainableInspection */
    expect(FullName::field(['name' => 'Name', 'attribute' => 'fName']))
        ->name->toBe('Name')
        ->attribute->toBe('fName');
});

it('can have a custom resolver using field params', closure: function () {
    expect(FullName::field())->resolveCallback->toBeNull();

    $field = FullName::field([
        'resolveCallback' => fn() => 'Foo Bar',
    ]);

    $field->resolve(User::factory()->create());

    expect($field->value)->toBe('Foo Bar');
});
