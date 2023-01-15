# Eweso standards

Thir library provides workflows, coding standard configuration and a general
documentation about package naming, structure, documentation and defines
basic .gitignore and composer.json content. It also describes how
CI tools such as PHP-CS-Fixer and PHPUnit must be included to the project
and how code should be documentated.

## Package naming and structure

The root directory for PHP library package classes is `/src`. The autoloader
must follow PSR-4 standard in the composer.json and start match follwing
namespace naming schema: 

`Eweso\{{PackageName}}`

Tests must be located in the `/tests` directory ans also follow the PSR-4
autloading standard. The namespace naming schema must follow the package's
namespace schema:

`Eweso\{{PackageName}}\Tests`

A package name must include the `eweso-` prefix in its name.

## Documentation

Eweso's documentation is based on ReStructuredText files located in the 
`/docs` directory of the package's root directory. Since various
definitions for titles exists, titles are limited to maximum 3 levels
and must follow the syntax below:

```rst
========= 
Heading 1
=========

Heading 2
---------

Heading 3
~~~~~~~~~
```

## Development Tools

Tools like `PHP-CS-Fixer` and `PHPUnit` must be installed via `Phive` to the
`/tools` directory. Autoloading via the package's composer.json is not allowed
to avoid package version conflicts. It is recommende to add the following 
scripts to the `composer.json` file.

```json
{
    "scripts": {
        "cs-fix": [
            "php tools/php-cs-fixer.phar fix --config=./.php-cs-fixer.php --ansi"
        ],
        "cs-check": [
            "php tools/php-cs-fixer.phar fix --dry-run --diff --config=./.php-cs-fixer.php --ansi"
        ],
        "test": [
            "php tools/phpunit.phar --colors=always --configuration=./phpunit.xml --no-coverage"
        ],
        "install-phive-tools": [
            "phive install phpunit php-cs-fixer",
            "mv tools/phpunit tools/phpunit.phar",
            "mv tools/php-cs-fixer tools/php-cs-fixer.phar"
        ]
    }
}
```

## Coding standard (style)

Coding standard (CS) check and fix is based on `PHP-CS-Fixer`. To use the current
CS ruleset add `eweso/eweso-standards` to the required dependencies of 
the package and create `.php-cs-fixer.php` in the package's root directory
with following content:

```php
<?php

declare(strict_types=1);

// Configure a finder
$finder = PhpCsFixer\Finder::create();

// Import CS config factory
$configFactory = include __DIR__ . '/vendor/eweso/eweso-standards/cs.config.php';

return $configFactory($finder);
```

## Unit tests

Unit tests are based on PHPUnit version 9.5+ and must be fully configured via 
`phpunit.xml` located in the packages's root directory. 
The following `phpunit.xml` is a basic but fully functional example:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit
        cacheResult="false"
        bootstrap="./vendor/autoload.php"
        colors="true"
        stopOnFailure="true">
    <testsuites>
        <testsuite name="Library Tests">
            <directory>./tests</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

## Workflows

There are currently two workflows supported that should be included in the 
package's workflow located under `./github/workflows/ci.yml` with following
content:

```yaml
name: CI
on: [ push, pull_request ]

jobs:
  test:
    name: Unit Tests
    uses: eweso/eweso-standards/.github/workflows/unit-test.yml@main
  cs-check:
    name: Coding Standard
    uses: eweso/eweso-standards/.github/workflows/cs-check.yml@main
```