<?php

use Jhavenz\NovaExtendedFields\Internet;
use Jhavenz\NovaExtendedFields\Named;

return [
    'fields' => [
        Named\FullName::class => [
            'name' => 'Full Name',
            'attribute' => 'full_name',
            'resolveCallback' => null,
            'behaviors' => [
                'sortable' => true,
                'creationRules' => [],
                'updateRules' => ['sometimes', 'nullable', 'string', 'min:6', 'max:50'],
                'rules' => ['required', 'string', 'min:6', 'max:50'],
            ],
        ],
        Named\FirstName::class => [
            'name' => 'First Name',
            'attribute' => 'first_name',
            'resolveCallback' => null,
            'behaviors' => [
                'sortable' => true,
                'creationRules' => [],
                'updateRules' => ['sometimes', 'nullable', 'string', 'min:6', 'max:50', 'email'],
                'rules' => ['required', 'string', 'min:6', 'max:50'],
            ],
        ],
        Named\LastName::class => [
            'name' => 'Last Name',
            'attribute' => 'last_name',
            'resolveCallback' => null,
            'behaviors' => [
                'sortable' => true,
                'creationRules' => [],
                'updateRules' => ['sometimes', 'nullable', 'string', 'min:6', 'max:50', 'email'],
                'rules' => ['required', 'string', 'min:6', 'max:50'],
            ],
        ],
        Named\DisplayName::class => [
            'name' => 'Display Name',
            'attribute' => 'display_name',
            'resolveCallback' => null,
            'behaviors' => [
                'sortable' => true,
                'creationRules' => [],
                'updateRules' => ['sometimes', 'nullable', 'string', 'min:6', 'max:50', 'email'],
                'rules' => ['required', 'string', 'min:6', 'max:50'],
            ],
        ],
        Internet\Email::class => [
            'name' => 'Email',
            'attribute' => 'email',
            'resolveCallback' => null,
            'behaviors' => [
                'sortable' => true,
                'creationRules' => [],
                'updateRules' => ['sometimes', 'nullable', 'min:6', 'max:50', 'email',/** 'unique:<table>,email' ? */],
                'rules' => ['required', 'min:6', 'max:50', 'email',/** 'unique:<table>,email' ? */],
            ],
        ],
    ],
];
