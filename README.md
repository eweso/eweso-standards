# Eweso standards

This library provides workflows, coding standards and general information about package naming, structure and documentation, as well as information about basic `.gitignore` and `composer.json` setup. It also describes how CI tools like `PHP-CS-Fixer` and `PHPUnit` must be used in packages.

## Package structure

```
# Package folders
/assets     (Contains package.json, node_modules, SASS and TS source)
/config     (Contains configuration for the PHP module)
/docs       (Contains AsciiDoc documentation)
/lang       (Contains POT, PO and MO gettext files)
/src        (Contains PSR-4 compliant PHP source code)
/public     (Contains CSS, JS and other public resources)
/tests      (Contains PSR-4 compliant PHP tests)
/templates  (Contains PHTML template files)

# Vendor folders
/tools      (Contains dev tools like PHPUnit and PHP-CS-Fixer)
/vendor     (Contains composer packages)

# Package files
.gitignore
.php-cs-fixer.php
composer.json
phpunit.xml
README.md
```

## Package naming

All package names must start with the prefix `eweso-`. Classes and namespaces must follow the `PSR-1` basic coding standard. The following naming convention applies to library packages:

```
/src    -> Eweso\{{PackageName}}
/tests  -> Eweso\{{PackageName}}\Tests
```

## Documentation

Eweso's documentation is based on the AsciiDoc standard. The documentation files must be placed in the `/docs` directory.

## Development Tools

Tools like `PHP-CS-Fixer` and `PHPUnit` must be installed via `Phive` to the `/tools` directory. Autoloading via the package's composer.json is not allowed to avoid package version conflicts. It is recommended to add the following scripts to the `composer.json` file.

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

## Coding standard

The coding standard (CS) is maintained with `PHP-CS-Fixer` and is based on the slightly modified `PSR-12` standard. To use the current coding standard ruleset, add `eweso/eweso-standards` to the required dependencies of the package and create `.php-cs-fixer.php` in the root directory of the package with following content:

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

Unit tests are based on `PHPUnit` version 9.5+ and must be fully configured via `phpunit.xml` located in the package root directory. The following `phpunit.xml` is a basic but fully functional example:

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

## CI Workflows

There are currently two supported CI workflows that should be included in the package's workflow configuration located at `./github/workflows/ci.yml` with the following content:

```yaml
name: CI
on: [ push, pull_request ]

jobs:
  test:
    name: Unit Tests
    uses: eweso/eweso-standards/.github/workflows/unit-test.yml@main
    secrets: inherit
  cs-check:
    name: Coding Standard
    uses: eweso/eweso-standards/.github/workflows/cs-check.yml@main
    secrets: inherit
```
