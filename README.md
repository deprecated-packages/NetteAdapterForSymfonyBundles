# Register Symfony bundles as Nette extension

[![Build Status](https://img.shields.io/travis/Symplify/SymfonyBundlesExtension.svg?style=flat-square)](https://travis-ci.org/Symplify/SymfonyBundlesExtension)
[![Quality Score](https://img.shields.io/scrutinizer/g/Symplify/SymfonyBundlesExtension.svg?style=flat-square)](https://scrutinizer-ci.com/g/Symplify/SymfonyBundlesExtension)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Symplify/SymfonyBundlesExtension.svg?style=flat-square)](https://scrutinizer-ci.com/g/Symplify/SymfonyBundlesExtension)
[![Downloads](https://img.shields.io/packagist/dt/Symplify/symfony-bundles-extension.svg?style=flat-square)](https://packagist.org/packages/Symplify/symfony-bundles-extension)
[![Latest stable](https://img.shields.io/packagist/v/Symplify/symfony-bundles-extension.svg?style=flat-square)](https://packagist.org/packages/Symplify/symfony-bundles-extension)

## Install

Via Composer:

```sh
$ composer require Symplify/symfony-bundles-extension
```

Register extension in your `config.neon`:

```yaml
extensions:
	symfonyBundles: Symplify\SymfonyBundlesExtension\DI\SymfonyBundlesExtension
```


## Usage

Register Symfony bundles just like Nette extensions:

```yaml
symfonyBundles:
	bundles:
		# list all bundles
		alice: Hautelook\AliceBundle\HautelookAliceBundle
	parameters:
		# and it's parameters (bound by same key name)
		alice:
			locale: cs_CZ
```

That's it!


## Features

### Tags

```yaml
extensions:
	symfonyBundles: Symplify\SymfonyBundlesExtension\DI\SymfonyBundlesExtension

services:
	-
		class: Symplify\SymfonyBundlesExtension\Tests\TacticianBundle\NetteTagsSource\SomeCommandHandler
		tags:
			tactician.handler:
				- [command: Symplify\SymfonyBundlesExtension\Tests\TacticianBundle\NetteTagsSource\SomeCommand]

symfonyBundles:
	bundles:
		- League\Tactician\Bundle\TacticianBundle
```


### Service references

```yaml
extensions:
	symfonyBundles: Symplify\SymfonyBundlesExtension\DI\SymfonyBundlesExtension

services:
	- Symplify\SymfonyBundlesExtension\Tests\Container\ParametersSource\CustomMiddleware

symfonyBundles:
	bundles:
		tactician: League\Tactician\Bundle\TacticianBundle

	parameters:
		tactician:
			commandbus:
				default:
					middleware:
						# this is reference to service registered in Nette
						- @Symplify\SymfonyBundlesExtension\Tests\Container\ParametersSource\CustomMiddleware
						- tactician.middleware.command_handler
```

## Testing

```bash
composer check-cs # see "scripts" section of composer.json for more details 
vendor/bin/phpunit
```


## Contributing

Rules are simple:

- new feature needs tests
- all tests must pass
- 1 feature per PR

We would be happy to merge your feature then!
