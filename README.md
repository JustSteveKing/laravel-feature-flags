# Laravel Feature Flags

[![Software License][ico-license]](LICENSE.md)
[![PHP Version](https://img.shields.io/packagist/php-v/juststeveking/laravel-feature-flags.svg?style=flat-square)](https://php.net)
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

A simple to use Feature Flag package for Laravel 

## Installation

```bash
composer require juststeveking/laravel-feature-flags
```

## Usage

This package allows you to manage user features and feature groups in a database.


To use this package your User model needs to have the `HasFeatures` trait:

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use JustSteveKing\Laravel\FeatureFlags\Concerns\HasFeatures;

class User extends Authenticatable
{
    use HasFeatures;
}
```

This will allow you to manage features and feature groups on your user model.

A *User* can belong to many *Feature Groups*, but can also be assigned access to specific *Features*.

### Working with Feature Groups

```php
// This will create the Feature Group if not already created and attach the user to it.
auth()->user()->addToGroup('beta testers');

// You can check if a user is a member of a feature group
auth()->user()->inGroup('beta testers');

// You can also get a user to leave a feature group
auth()->user()->leaveGroup('beta testers');
```

### Working with Features

```php
// This will create the Feature if not already created and attach the user to it.
auth()->user()->giveFeature('run reports');

// You can check if a user has a specific feature
auth()->user()->hasFeature('run reports');

// You can also remove a feature for a user
auth()->user()->removeFeature('run reports');
```
## Testing

``` bash
$ composer run test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email juststevemcd@gmail.com instead of using the issue tracker.

## Credits

- [Steve McDougall][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/juststeveking/laravel-feature-flags.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/juststeveking/laravel-feature-flags.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/juststeveking/laravel-feature-flags
[link-downloads]: https://packagist.org/packages/juststeveking/laravel-feature-flags

[link-author]: https://github.com/JustSteveKing
[link-contributors]: ../../contributors