# auto translate for filament

[![Latest Version on Packagist](https://img.shields.io/packagist/v/darko/filament-auto-translate.svg?style=flat-square)](https://packagist.org/packages/darko/filament-auto-translate)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/darko/filament-auto-translate/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/darko/filament-auto-translate/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/darko/filament-auto-translate/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/darko/filament-auto-translate/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/darko/filament-auto-translate.svg?style=flat-square)](https://packagist.org/packages/darko/filament-auto-translate)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require darko/filament-auto-translate
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-auto-translate-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-auto-translate-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-auto-translate-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$filamentAutoTranslate = new Darko\FilamentAutoTranslate();
echo $filamentAutoTranslate->echoPhrase('Hello, Darko!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Boris Hu](https://github.com/hubo502)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
