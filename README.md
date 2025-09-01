# Zerotoprod\Psr4VarName

![](art/logo.png)

[![Repo](https://img.shields.io/badge/github-gray?logo=github)](https://github.com/zero-to-prod/psr4-varname)
[![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/zero-to-prod/psr4-varname/test.yml?label=test)](https://github.com/zero-to-prod/psr4-varname/actions)
[![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/zero-to-prod/psr4-varname/backwards_compatibility.yml?label=backwards_compatibility)](https://github.com/zero-to-prod/psr4-varname/actions)
[![Packagist Downloads](https://img.shields.io/packagist/dt/zero-to-prod/psr4-varname?color=blue)](https://packagist.org/packages/zero-to-prod/psr4-varname/stats)
[![php](https://img.shields.io/packagist/php-v/zero-to-prod/psr4-varname.svg?color=purple)](https://packagist.org/packages/zero-to-prod/psr4-varname/stats)
[![Packagist Version](https://img.shields.io/packagist/v/zero-to-prod/psr4-varname?color=f28d1a)](https://packagist.org/packages/zero-to-prod/psr4-varname)
[![License](https://img.shields.io/packagist/l/zero-to-prod/psr4-varname?color=pink)](https://github.com/zero-to-prod/psr4-varname/blob/main/LICENSE.md)
[![wakatime](https://wakatime.com/badge/github/zero-to-prod/psr4-varname.svg)](https://wakatime.com/badge/github/zero-to-prod/psr4-varname)
[![Hits-of-Code](https://hitsofcode.com/github/zero-to-prod/data-model-adapter-openapi30?branch=main)](https://hitsofcode.com/github/zero-to-prod/data-model-adapter-openapi30/view?branch=main)

- [Introduction](#introduction)
- [Requirements](#requirements)
- [Installation](#installation)
- [Documentation Publishing](#documentation-publishing)
  - [Automatic Documentation Publishing](#automatic-documentation-publishing)
- [Usage](#usage)
- [Local Development](./LOCAL_DEVELOPMENT.md)
- [Contributing](#contributing)

## Introduction

Generates a valid PSR-4 Compliant variable name from a string.

## Requirements

- PHP 7.1 or higher.

## Installation

Install `Zerotoprod\Psr4VarName` via [Composer](https://getcomposer.org/):

```shell
composer require zero-to-prod/psr4-varname
```

This will add the package to your project's dependencies and create an autoloader entry for it.

## Documentation Publishing

You can publish this README to your local documentation directory.

This can be useful for providing documentation for AI agents.

This can be done using the included script:

```bash
# Publish to default location (./docs/zero-to-prod/psr4-varname)
vendor/bin/zero-to-prod-psr4-varname

# Publish to custom directory
vendor/bin/zero-to-prod-psr4-varname /path/to/your/docs
```

### Automatic Documentation Publishing

You can automatically publish documentation by adding the following to your `composer.json`:

```json
{
    "scripts": {
        "post-install-cmd": [
            "zero-to-prod-psr4-varname"
        ],
        "post-update-cmd": [
            "zero-to-prod-psr4-varname"
        ]
    }
}
```

## Usage

```php
use Zerotoprod\Psr4Classname\VarName;

VarName::generate('weird%characters*in^name'); // 'weird_characters_in_name';
```

## Contributing

Contributions, issues, and feature requests are welcome!
Feel free to check the [issues](https://github.com/zero-to-prod/psr-varname/issues) page if you want to contribute.

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Commit changes (`git commit -m 'Add some feature'`).
4. Push to the branch (`git push origin feature-branch`).
5. Create a new Pull Request.
