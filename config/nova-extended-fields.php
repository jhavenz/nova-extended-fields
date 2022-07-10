<?php

use Jhavenz\NovaExtendedFields\Shared;

return [
    'fields' => [
        Shared\FullName::class => [
            'name' => 'Name',
            'attribute' => 'full_name',
            'resolveCallback' => null,
        ],
    ],
];
