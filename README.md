# SymfonyBundlesExtensions

[![Build Status](https://img.shields.io/travis/Symnedi/SymfonyBundlesExtension.svg?style=flat-square)](https://travis-ci.org/Symnedi/SymfonyBundlesExtension)
[![Quality Score](https://img.shields.io/scrutinizer/g/Symnedi/SymfonyBundlesExtension.svg?style=flat-square)](https://scrutinizer-ci.com/g/Symnedi/SymfonyBundlesExtension)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Symnedi/SymfonyBundlesExtension.svg?style=flat-square)](https://scrutinizer-ci.com/g/Symnedi/SymfonyBundlesExtension)
[![Downloads this Month](https://img.shields.io/packagist/dm/symnedi/symfony-bundles-extension.svg?style=flat-square)](https://packagist.org/packages/symnedi/symfony-bundles-extension)
[![Latest stable](https://img.shields.io/packagist/v/symnedi/symfony-bundles-extension.svg?style=flat-square)](https://packagist.org/packages/symnedi/symfony-bundles-extension)

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
	- Hautelook\AliceBundle\HautelookAliceBundle
```

That's it!
