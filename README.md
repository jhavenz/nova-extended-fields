### File-based fields for Laravel Nova

Personally, I'm not a fan of configuring all of my Nova fields within the 'fields' array that Nova expects.

Additionally, most of the fields used across Nova projects tend to have a lot in common...

So isn't there a better way than having to repeat all this logic for each project, plus needing to
configure each field with an array and having to (visually) parse through all fields to find the one
you're looking for each time? That's what this package aims to fix.

So, instead of something like this in your Nova project:
_fyi...`rescueQuietly()` is just a helper I use a lot who's 3rd argument (`$report: true`) for the `rescue` method is
set to `false`_

> Important:
> This package is still in development/pre-release mode. Please do not use in production yet.

```php
<?php

namespace App\Nova\Resources;

use Laravel\Nova\Fields\Text;

class NovaUser extends Resource
{
    //...
    
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
}

```

**To something like this:**

```php
use App\Nova\Resources\Fields\Internet\Name;

namespace App\Nova\Resources;

use Laravel\Nova\Fields\Text;

class NovaUser extends Resource
{
    public function fields(NovaRequest $request): array
    {
        return [
            FullName::field(),
            
            // ...other fields
        ];
    }
}
```

aahhh 🤗, that's much better!

Let's dive in...

---

### Installation

You can install the package via composer:

```bash
composer require jhavenz/nova-extended-fields
```

### Config

You can publish the config file with:

```bash
php artisan vendor:publish --tag="nova-extended-fields"
```

### General Usage and Configuration

As mentioned, each extended field now has its own class and can be easily configured using this package's config file.

As you'll see, I've listed out some sane defaults which you'll find in the config file.
Feel free to change any of the configurations to the needs of your app.

Additionally, you'll notice that the static `::field()` method is used whenever instantiating a field. This is because
each extended field has its own binding within the container, bound by its respective class-string.
e.g. the `FullName` field can be resolved using `app(FullName::class)` and so on.

Should you need to completely rewire the way that a field is being resolved, you can overwrite any/all the bindings as
you see fit (though this shouldn't be necessary most of the time).

_note: you can look at the tests for any examples_

---

### Advanced Usage

In situations where you require custom logic that's beyond the capabilities of what we can
pass into the config file or the `::fields()` parameters, you can extend any of the
fields in this package and begin writing your own custom logic. This opens up a lot of doors too!

One example of this would be the need to use custom `fillUsing` logic on one of your fields.

This would look something like this:

```php
<?php

namespace App\Nova\Resources\Fields\Shared;

use Jhavenz\NovaExtendedFields\Named\FullName;

class MyAppFullName extends FullName
{
    /**
     * Maybe our database only has [first_name] and [last_name] columns. 
     * So we need to split the full name before saving it the database...
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        [$firstName, $lastName] = $request->input($requestAttribute);
          
        $model->first_name = $firstName;
        $model->last_name = $lastName;
        
        # Note:
        # Laravel will call the save() method automatically after this method completes
    }
}
```

> **Tip:**
> There are a lot of other methods on a `Fields` instance that, once knowing the specific type of field we're working
> with, can then be overridden which helps to significantly DRY up your code!
>
> In cases where you may have two different models that share the same field, it helps to create a specific file for
> each model. For example, a `UserEmail` field may have its own requirements/logic vs a `CompanyEmail` field.
> Creating a file for each of these models allows you to write the logic that's specific to each one.
> These fields can then be used as the source of truth throughout all your Nova projects!

---

### Developer Note

I've only recently started (as of 07/2022) pulling out the common fields that I use in my own Nova projects, but
eventually I plan
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

---

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
