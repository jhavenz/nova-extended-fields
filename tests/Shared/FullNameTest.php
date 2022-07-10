<?php

use Jhavenz\NovaExtendedFields\Shared\FullName;

it('has default name and attributes', function () {
    expect(FullName::field())
        ->name->toBe('Name')
        ->attribute->toBe('full_name');
});

it('can have custom name and attributes using the config file', function () {
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
