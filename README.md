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

## Restrictions and warnings

1. This package was be tested on MySQL, SQLite and PostgreSQL and it is compatible with them
2. Because this package is working directly with database to minimize count of queries after use 
`moveItem()` or `insertItem()` you should refresh loaded entities. **Attention! you must save all
updates before call this methods to avoids conflicts**

## Usages

Package provide main `Kappa\DoctrineMPTT\TraversableManager` which can be used for all manipulations.
All operations are performed over entity which is instance of `Kappa\DoctrineMPTT\Entities\TraversableInterface`.


### Entity

You can use own entity but, your entity must implement `Kappa\DoctrineMPTT\Entities\TraversableInterface` interface.
For easier implementation you can use `Kappa\DoctrineMPTT\Entities\Traversable` trait which implements all
requires methods and columns.

### Manager

### Queries

1. `Kappa\DoctrineMPTT\Queries\Objects\Selectors\GetAll` - returns all items sorted for scalable listing
2. `Kappa\DoctrineMPTT\Queries\Objects\Selectors\GetParents` - returns all parents for actual item
3. `Kappa\DoctrineMPTT\Queries\Objects\Selectors\GetChildren` - returns all children for actual item
