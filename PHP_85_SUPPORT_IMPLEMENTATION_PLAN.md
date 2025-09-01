# PHP 8.5 Support Implementation Plan

## Overview

This document provides a comprehensive plan for adding PHP 8.5 support to PHP projects based on the changes implemented in the `feat/php85` branch. This plan can be applied to other projects in the zero-to-prod ecosystem or any PHP project that uses similar development and testing infrastructure.

## Summary of Changes

The implementation adds PHP 8.5 support across the entire development and CI/CD pipeline, plus additional tooling improvements:

- **GitHub Actions CI/CD**: Updated test matrix to include PHP 8.5
- **Docker Development Environment**: Added complete PHP 8.5 Docker setup with debug support
- **Local Testing**: Updated test scripts to include PHP 8.5
- **Documentation Tooling**: Added binary for publishing README to documentation directories
- **Composer Configuration**: Enhanced with binary definitions and dependency reordering
- **README Documentation**: Added comprehensive documentation publishing instructions

## Implementation Steps

### 1. Update GitHub Actions Workflow

**File**: `.github/workflows/test.yml`

**Changes Required**:

```yaml
# Update the PHP version matrix to include PHP 8.5
strategy:
  matrix:
    php-version: [ "8.5", "8.4", "8.3", "8.2", "8.1", "8.0", "7.4", "7.3", "7.2", "7.1" ]

    # Update checkout action to latest version
    - uses: actions/checkout@v5  # Updated from v4
```

**Key Considerations**:

- Place PHP 8.5 at the beginning of the matrix for early failure detection
- Ensure proper spacing consistency in YAML arrays
- Update checkout action to the latest stable version for better compatibility

### 2. Docker Development Environment Setup

#### 2.1 Create PHP 8.5 Docker Configuration

**Directory Structure**:

```
docker/
└── 8.5/
    ├── .gitignore
    ├── Dockerfile
    └── php.ini
```

#### 2.2 Docker Files Content

**File**: `docker/8.5/.gitignore`

```
.phpunit.result.cache
```

**File**: `docker/8.5/Dockerfile`

```dockerfile
FROM php:8.5-rc-alpine AS builder

RUN apk add --no-cache \
    git \
    unzip \
    $PHPIZE_DEPS

RUN git config --global --add safe.directory /app

WORKDIR /app

FROM builder AS composer

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer

FROM builder AS debug

RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    linux-headers \
  && pecl channel-update pecl.php.net \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && apk del .build-deps \
  && rm -rf /tmp/* /var/cache/apk/*

FROM php:8.5-rc-alpine AS base

WORKDIR /app

CMD ["bash"]
```

**File**: `docker/8.5/php.ini`

```ini
zend_extension = xdebug.so
xdebug.mode = debug
xdebug.start_with_request = yes
xdebug.client_host = host.docker.internal
xdebug.client_port = 9003
```

#### 2.3 Update Docker Compose Configuration

**File**: `docker-compose.yml`

**Add these services TO THE END OF THE FILE!**:

```yaml
  php8.5:
    volumes:
      - ./:/app:delegated
      - ./.vendor/php8.5:/app/vendor
    build:
      context: ./docker/8.5
      target: base

  debug8.5:
    extends:
      service: php8.5
    volumes:
      - ./:/app:delegated
      - ./docker/8.5:/usr/local/etc/php
      - ./.vendor/php8.5:/app/vendor
    build:
      target: debug

  composer8.5:
    extends:
      service: php8.5
    volumes:
      - ./:/app:delegated
      - ./.vendor/php8.5:/app/vendor
    build:
      target: composer
```

### 3. Update Local Testing Scripts

**File**: `test.sh`

**Change**:

```bash
# Update the PHP versions array to include 8.5 at the beginning
PHP_VERSIONS=("8.5" "8.4" "8.3" "8.2" "8.1" "8.0" "7.4" "7.3" "7.2" "7.1")
```

### 4. Additional Documentation and Tooling Improvements

#### 4.1 Create Documentation Publisher Binary

**File**: `bin/zero-to-prod-{package-name}` (e.g., `bin/zero-to-prod-validate-url`)

```php
#!/usr/bin/env php
<?php

/**
 * Zero-to-Prod Validate URL Documentation Publisher
 *
 * Publishes the README.md file to the user's documentation directory.
 */

require getcwd().'/vendor/autoload.php';

use Zerotoprod\PackageHelper\PackageHelper;

$target_path = getcwd().'/docs/zero-to-prod/{package-name}/';
if (isset($argv[1])) {
    $target_path = rtrim($argv[1], '/').'/';
}

$source_file = __DIR__.'/../README.md';

if (!file_exists($source_file)) {
    fwrite(STDERR, "Error: Not found $source_file\n");
    exit(1);
}

try {
    PackageHelper::copy($source_file, $target_path);
    exit(0);
} catch (RuntimeException $e) {
    fwrite(STDERR, "Error: ".$e->getMessage()."\n");
    exit(1);
}
```

#### 4.2 Update Composer Configuration

**File**: `composer.json`

**Verify and add required dependency** (the documentation publisher binary requires this package):

```json
{
    "require": {
        "zero-to-prod/package-helper": "^1.1.3"
    }
}
```

**Add binary configuration** (format: `bin/zero-to-prod-{package-name}` based on the `name` field):

```json
{
    "bin": [
        "bin/zero-to-prod-{package-name}"
    ]
}
```

**Example for validate-url package**:
```json
{
    "bin": [
        "bin/zero-to-prod-validate-url"
    ]
}
```

**Complete require section example** (ensure `zero-to-prod/package-helper` is present):

```json
{
    "require": {
        "php": ">=7.1",
        "zero-to-prod/package-helper": "^1.1.3"
    }
}
```

#### 4.3 Update README Documentation

**File**: `README.md`

**Add documentation publishing section** into the Table of Contents:

**Update the Contents section to include nested structure after Installation:**

```markdown
## Contents

...
- [Installation](#installation)
- [Documentation Publishing](#documentation-publishing)
    - [Automatic Documentation Publishing](#automatic-documentation-publishing)
      ...
```

**Add the Documentation Publishing section AFTER the Installation section!:**

```markdown
## Documentation Publishing

You can publish this README to your local documentation directory.

This can be useful for providing documentation for AI agents.

This can be done using the included script:

```bash
# Publish to default location (./docs/zero-to-prod/{package-name})
vendor/bin/zero-to-prod-{package-name}

# Publish to custom directory
vendor/bin/zero-to-prod-{package-name} /path/to/your/docs
```

### Automatic Documentation Publishing

You can automatically publish documentation by adding the following to your `composer.json`:

```json
{
    "scripts": {
        "post-install-cmd": [
            "zero-to-prod-{package-name}"
        ],
        "post-update-cmd": [
            "zero-to-prod-{package-name}"
        ]
    }
}
```
