<?php

use Jhavenz\NovaExtendedFields\Contactable;

return [
    'fields' => [
        Contactable\FullName::class => [
            'name' => 'Name',
            'attribute' => 'full_name',
            'resolveCallback' => null,
            'configurables' => [
                'sortable' => true,
                'creationRules' => [],
                'updateRules' => [],
                'rules' => [],
            ],
        ],
        Contactable\Email::class => [
            'name' => 'Email',
            'attribute' => 'email',
            'resolveCallback' => null,
            'configurables' => [
                'sortable' => true,
                'creationRules' => ['required', 'max:255', 'min:6', 'email',/** 'unique:<table>,email' ? */],
                'updateRules' => ['sometimes', 'nullable', 'max:255', 'min:6', 'email',/** 'unique:<table>,email' ? */],
                'rules' => [],
            ],
        ],
    ],
];
