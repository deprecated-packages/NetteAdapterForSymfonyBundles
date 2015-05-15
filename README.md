# SymfonyBundlesExtension

[![Build Status](https://img.shields.io/travis/Symnedi/SymfonyBundlesExtension.svg?style=flat-square)](https://travis-ci.org/Symnedi/SymfonyBundlesExtension)
[![Quality Score](https://img.shields.io/scrutinizer/g/Symnedi/SymfonyBundlesExtension.svg?style=flat-square)](https://scrutinizer-ci.com/g/Symnedi/SymfonyBundlesExtension)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Symnedi/SymfonyBundlesExtension.svg?style=flat-square)](https://scrutinizer-ci.com/g/Symnedi/SymfonyBundlesExtension)
[![Downloads this Month](https://img.shields.io/packagist/dm/symnedi/symfony-bundles-extension.svg?style=flat-square)](https://packagist.org/packages/symnedi/symfony-bundles-extension)
[![Latest stable](https://img.shields.io/packagist/v/symnedi/symfony-bundles-extension.svg?style=flat-square)](https://packagist.org/packages/symnedi/symfony-bundles-extension)

Register Symfony Bundles as Nette Extensions with ease.


## Install

Via Composer:

```sh
$ composer require symnedi/symfony-bundles-extension
```

Register extension in your `config.neon`:

```yaml
extensions:
	symfonyBundles: Symnedi\SymfonyBundlesExtension\DI\SymfonyBundlesExtension
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
	symfonyBundles: Symnedi\SymfonyBundlesExtension\DI\SymfonyBundlesExtension

services:
	-
		class: Symnedi\SymfonyBundlesExtension\Tests\TacticianBundle\NetteTagsSource\SomeCommandHandler
		tags:
			tactician.handler:
				- [command: Symnedi\SymfonyBundlesExtension\Tests\TacticianBundle\NetteTagsSource\SomeCommand]

symfonyBundles:
	bundles:
		- League\Tactician\Bundle\TacticianBundle
```


### Service references

```yaml
extensions:
	symfonyBundles: Symnedi\SymfonyBundlesExtension\DI\SymfonyBundlesExtension

services:
	- Symnedi\SymfonyBundlesExtension\Tests\Container\ParametersSource\CustomMiddleware

symfonyBundles:
	bundles:
		tactician: League\Tactician\Bundle\TacticianBundle

	parameters:
		tactician:
			commandbus:
				default:
					middleware:
						# this is reference to service registered in Nette
						- @Symnedi\SymfonyBundlesExtension\Tests\Container\ParametersSource\CustomMiddleware
						- tactician.middleware.command_handler
```
