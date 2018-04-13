KATSANA SDK for PHP
==============

[![Build Status](https://travis-ci.org/katsana/katsana-sdk-laravel.svg?branch=master)](https://travis-ci.org/katsana/katsana-sdk-laravel)
[![Latest Stable Version](https://poser.pugx.org/katsana/katsana-sdk-laravel/v/stable)](https://packagist.org/packages/katsana/katsana-sdk-laravel)
[![Total Downloads](https://poser.pugx.org/katsana/katsana-sdk-laravel/downloads)](https://packagist.org/packages/katsana/katsana-sdk-laravel)
[![Latest Unstable Version](https://poser.pugx.org/katsana/katsana-sdk-laravel/v/unstable)](https://packagist.org/packages/katsana/katsana-sdk-laravel)
[![License](https://poser.pugx.org/katsana/katsana-sdk-laravel/license)](https://packagist.org/packages/katsana/katsana-sdk-laravel)
[![Coverage Status](https://coveralls.io/repos/github/katsana/katsana-sdk-laravel/badge.svg?branch=master)](https://coveralls.io/github/katsana/katsana-sdk-laravel?branch=master)

* [Installation](#installation)

## Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
    "require": {
        "katsana/katsana-sdk-laravel": "^1.0"
    }
}
```

And then run `composer install` from the terminal.

### Quick Installation

Above installation can also be simplify by using the following command:

    composer require "katsana/katsana-sdk-laravel=^1.0"

## Configuration

Next add the service provider in `config/app.php`.

```php
'providers' => [

    // ...

    Katsana\ServiceProvider::class,

],
```

### Aliases

You might want to add `Katsana\Katsana` to class aliases in `config/app.php`:

```php
'aliases' => [

    // ...

    'Katsana' => Katsana\Katsana::class,

],
```

### Configuration

#### Using Client ID & Secret

Next add the configuration in `config/services.php`.

```php
<?php 

return [

    // ...

    'katsana' => [
        'client_id' => env('KATSANA_CLIENT_ID'),
        'client_secret' => env('KATSANA_CLIENT_SECRET'),
    ],
];
```

#### Using Personal Access Token

Next add the configuration in `config/services.php`.

```php
<?php 

return [

    // ...

    'katsana' => [
        'access_token' => env('KATSANA_ACCESS_TOKEN'),
    ],
];
```
