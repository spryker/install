# Install Module
[![Build Status](https://travis-ci.org/spryker/install.svg?branch=master)](https://travis-ci.org/spryker/install)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/spryker/install/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/spryker/install/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/spryker/install/badge.svg?branch=master)](https://coveralls.io/github/spryker/install?branch=master)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.3-8892BF.svg)](https://php.net/)
[![License](https://img.shields.io/github/license/spryker/install.svg)](https://packagist.org/packages/spryker/install)

Install module handles project setup using recipes per environment.

This is for local development, staging and production deployment. For security reasons the environment default should always be configured to `production`. Your local `APPLICATION_ENV` env setup should then overwrite as per your non-production environment.

## Installation

```
composer require spryker/install
```

## Documentation
https://documentation.spryker.com/v20/docs/install-core-module
