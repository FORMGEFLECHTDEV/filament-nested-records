# This is my package filament-nested-records

[![Latest Version on Packagist](https://img.shields.io/packagist/v/formgeflecht/filament-nested-records.svg?style=flat-square)](https://packagist.org/packages/formgeflecht/filament-nested-records)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/formgeflecht/filament-nested-records/run-tests?label=tests)](https://github.com/formgeflecht/filament-nested-records/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/formgeflecht/filament-nested-records/Check%20&%20fix%20styling?label=code%20style)](https://github.com/formgeflecht/filament-nested-records/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/formgeflecht/filament-nested-records.svg?style=flat-square)](https://packagist.org/packages/formgeflecht/filament-nested-records)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require formgeflecht/filament-nested-records
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-nested-records-config"
```

This is the contents of the published config file:

```php
return [
  'title_column' => 'title'
];
```

## Usage

### Update migration-file

Add `parent_id`-field to your resource model.

```php
Schema::table('your_resource_model', function (Blueprint $table) {
  // ...
  $table->foreignId('parent_id')->nullable();
});
```

### Preparing resource model

Add `Formgeflecht\FilamentNestedRecords\Concerns\HasNestedRecords` trait to resource model.

```php
use Formgeflecht\FilamentNestedRecords\Concerns\HasNestedRecords;

class YourModel extends Model {
  use HasNestedRecords;
  
  // ...
}
```

### Preparing your resource class

After preparing your resource model, you must extend your resource's list-page. You can find your resource's list-pages in the Pages directory of each resource folder:

```php
use Formgeflecht\FilamentNestedRecords\Resources\Pages\NestedRecords;

class ListPosts extends NestedRecords
{
  // ...
}
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

- [Freerk van Zeijl](https://github.com/fvanzeijl)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
