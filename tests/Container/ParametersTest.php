<?php

namespace Symnedi\SymfonyBundlesExtension\Tests\Container;

use Nelmio\Alice\Loader\Yaml;
use Nette\DI\Container;
use PHPUnit_Framework_Assert;
use PHPUnit_Framework_TestCase;
use Symnedi\SymfonyBundlesExtension\Tests\ContainerFactory;
use Symnedi\SymfonyBundlesExtension\Tests\ContainerSource\ParameterStorage;


class ParametersTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Container
	 */
	private $container;


	public function __construct()
	{
		$this->container = (new ContainerFactory)->create();
	}


	public function testConstructorParameters()
	{
		/** @var ParameterStorage $parameterStorage */
		$parameterStorage = $this->container->getByType(ParameterStorage::class);
		$this->assertInstanceOf(ParameterStorage::class, $parameterStorage);

		$this->assertSame(1, $parameterStorage->getParameter());
		$this->assertSame([2, 3], $parameterStorage->getGroupOfParameters());
	}


	public function testBundleParameters()
	{
		/** @var Yaml $yamlLoader */
		$yamlLoader = $this->container->getByType(Yaml::class);
		$this->assertInstanceOf(Yaml::class, $yamlLoader);

		$this->assertSame('cs_CZ', PHPUnit_Framework_Assert::getObjectAttribute($yamlLoader, 'defaultLocale'));
	}

}
