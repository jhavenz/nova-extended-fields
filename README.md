### File-based fields for Laravel Nova

Personally, I'm not a fan of configuring all of my Nova fields within the 'fields' array that Nova expects.

Additionally, most of the fields used across Nova projects tend to have a lot in common...

So isn't there a better way than having to repeat all this logic for each project, plus needing to 
configure each field with an array and having to (visually) parse through all fields to find the one 
you're looking for each time? That's what this package aims to fix.

So, instead of something like this in your Nova project:
_fyi...`rescueQuietly()` is just a helper I use a lot who's 3rd argument (`$report: true`) for the `rescue` method is set to `false`_
```php

use Laravel\Nova\Fields\Text;

public function fields(NovaRequest $request): array
    {
        return [
            Text::make('Full Name')
                ->sortable()
                ->rules('required', 'string', 'min:6', 'max:25', function ($attr, $value, $fail) {
                    [$first, $last] = rescueQuietly(
                        fn() => explode(' ', $value),
                        fn() => $fail("Name is invalid. Be sure there's a space between your first and last name")
                    );
                    
                    if (count(explode(' ', $value)) > 2) { 
                        $fail("We don't allow more than 2 words for your Name. If needed, please join your first/last name. e.g. Wernher VonBraun")
                    }
                    
                    //etc.
                }),
                
            // ...other fields
        ];
    }
```

To something like this:
```php
use App\Nova\Resources\Fields\Shared\Name;

public function fields(NovaRequest $request): array
    {
        return [
            FullName::field(),
            // ...other fields
        ];
    }
```

And now the `Name` field lives in its own file, which would look something like this:
```php
<?php

namespace App\Nova\Resources\Fields\Shared;

use App\Nova\Resources\Concerns\ExtendedNovaField;
use Laravel\Nova\Fields\Text;

class FullName extends Text
{
    use ExtendedNovaField;

    public function __construct($name = null, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct(
            $name ?? 'Name',
            $attribute ?? 'full_name',
            $resolveCallback
        );
    }
}
```
_note: the default constructor params must be null so they're compatible with the Nova source code_

---

I've only recently started (as of 07/2022) pulling out the common fields that I use in my own Nova projects, but eventually I plan
to move all into this package.
You'll find a lot of the common fields in here, and I'd welcome contributions for more!

A non-exhaustive list of the fields included are: 
- FullName
- FirstName
- LastName
- Email
- NumericPrimaryKey
- UsersRole (based a very basic authorization system)
- UserPassword
- UserPasswordConfirmation
- Avatar

_More may have been added since the time of writing_

## Installation

You can install the package via composer:

```bash
composer require jhavenz/nova-extended-fields
```

## Additional Usage

```php
// TODO - explain field customizations in more detail...
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

All contributions are welcome!

## Security Vulnerabilities

Please email me at `mail@jhavens.tech` asap if any security vulnerabilities are discovered

## Credits

- [Jonathan Havens](https://github.com/jhavenz)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
