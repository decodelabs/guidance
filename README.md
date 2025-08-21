# Guidance

[![PHP from Packagist](https://img.shields.io/packagist/php-v/decodelabs/guidance?style=flat)](https://packagist.org/packages/decodelabs/guidance)
[![Latest Version](https://img.shields.io/packagist/v/decodelabs/guidance.svg?style=flat)](https://packagist.org/packages/decodelabs/guidance)
[![Total Downloads](https://img.shields.io/packagist/dt/decodelabs/guidance.svg?style=flat)](https://packagist.org/packages/decodelabs/guidance)
[![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/decodelabs/guidance/integrate.yml?branch=develop)](https://github.com/decodelabs/guidance/actions/workflows/integrate.yml)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-44CC11.svg?longCache=true&style=flat)](https://github.com/phpstan/phpstan)
[![License](https://img.shields.io/packagist/l/decodelabs/guidance?style=flat)](https://packagist.org/packages/decodelabs/guidance)

### Generalised UUID generation and parsing interface

Guidance provides a simplified interface for generating and parsing UUIDs of various types. It is designed to be used as a common interface for other libraries that need to work with UUIDs, without having to worry about the specifics of each type and implementation of the generator.

Guidance provides a stripped down front end over the [Ramsey UUID](https://uuid.ramsey.dev) library by default, but can be extended to support other implementations where required.

It also provides a simple interface for generating and parsing ULIDs, which are a more modern alternative to UUIDs.

---

## Installation

Install via Composer:

```bash
composer require decodelabs/guidance
```

## Usage

```php
use DecodeLabs\Guidance;
use DecodeLabs\Guidance\Uuid\Format;
use DecodeLabs\Monarch;

$guidance = Monarch::getService(Guidance::class);


// Generate a v4 UUID
$v4 = $guidance->createV4Uuid();
$version = $v4->version; // Version::V4

$string1 = (string)$v4; // Full UUID string
$string2 = $v4->shorten(Format::Base62); // Base 62 (default) encoded UUID
$string3 = $v4->shorten(Format::FlickrBase58); // Base 58 encoded UUID
echo $v4->bytes; // Raw bytes of the UUID

$new1 = $guidance->uuidFrom($string1); // Parse full UUID string
$new2 = $guidance->uuidFromShortString($string3, Format::FlickrBase58);

$ulid = $guidance->createUlid(); // Generate a ULID
echo $ulid->dateTime->format('Y-m-d H:i:s'); // Get the date and time from the ULID
```

## Licensing

Guidance is licensed under the MIT License. See [LICENSE](./LICENSE) for the full license text.
