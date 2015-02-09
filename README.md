# Kappa\DoctrineMPTT

[![Build Status](https://travis-ci.org/Kappa-org/DoctrineMPTT.svg?branch=master)](https://travis-ci.org/Kappa-org/DoctrineMPTT)

Modified Pre-order Tree Traversal doctrine implementation

## Requirements

Full list of dependencies you can get from [Composer config file](https://github.com/Kappa-org/DoctrineMPTT/blob/master/composer.json)

## Installation

The best way to install Kappa\DoctrineMPTT is using [Composer](https://getcomposer.org)

```shell
$ composer require kappa/doctrine-mptt:@dev
```

and register extension

```yaml
extensions:
    doctrineMPTT: Kappa\DoctrineMPTT\DI\DoctrineMPTTExtension
```

