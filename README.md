# Commerce Connect PHP SDK

This SDK provides simple access to the Commerce Connect API.

## Contents

- [Getting started](#getting-started)
- [Prerequisites](#prerequisites)
- [Creating a client](#creating-a-client)
- [Integrating with Laravel](#integrating-with-laravel)
- [Available methods](#available-methods)
- [Contributing](#contributing)

## Getting started

Install the SDK into your project using Composer.

```bash
composer config repositories.commerceconnect-sdk git git@github.com:arkade-digital/commerceconnect-sdk.git
composer require arkade/commerceconnect-sdk
```

## Prerequisites

To begin sending requests to Commerce Connect, you will need a few pieces of information.

- __Base URL__ This is the base URL where the Commerce Connect API is accessible from.
- __Email__ This is provided by Commerce Connect.
- __Token__ This is provided by Commerce Connect.

## Creating a client

> If you are using Laravel, skip to the [Integrating with Laravel](#integrating-with-laravel) section

To begin using the SDK, you will first need to create an authenticated client with the information you have obtained above.

```php
use Arkade\CommerceConnect;

$client = (new CommerceConnect\Client('http://api.example.com/'))
    ->setCredentials('email', 'token');
```

If you create a client without setting credentials, all your requests will be sent without appropriate authentication headers and will most likely result in an unauthorised response.

## Integrating with Laravel

This package ships with a Laravel specific service provider which allows you to set your credentials from your configuration file and environment.

### Registering the provider

Add the following to the `providers` array in your `config/app.php` file.

```php
Arkade\CommerceConnect\LaravelServiceProvider::class
```

### Adding config keys

In your `config/services.php` file, add the following to the array.

```php
'commerceconnect' => [
    'base_url' => env('COMMERCE_CONNECT_BASE_URL'),
    'email'    => env('COMMERCE_CONNECT_EMAIL'),
    'token'    => env('COMMERCE_CONNECT_TOKEN'),
]
```

### Adding environment keys

In your `.env` file, add the following keys.

```ini
COMMERCE_CONNECT_BASE_URL=
COMMERCE_CONNECT_EMAIL=
COMMERCE_CONNECT_TOKEN=
```

### Resolving a client

To resolve a fully authenticated client, you simply pull it from the service container. This can be done in a few ways.

#### Type hinting

```php
use Arkade\CommerceConnect;

public function yourControllerMethod(CommerceConnect\Client $client) {
    // Call methods on $client
}
```

#### Using the `app()` helper

```php
use Arkade\CommerceConnect;

public function anyMethod() {
    $client = app(CommerceConnect\Client::class);
    // Call methods on $client
}
```

## Available methods

Coming soon.

## Contributing

If you wish to contribute to this library, please submit a pull request and assign to a member of Capcom for review.

All public methods should be accompanied with unit tests.

### Testing

```bash
./vendor/bin/phpunit
```