<?php

namespace Symnedi\SymfonyBundlesExtension\Tests\Container;

use Hautelook\AliceBundle\Alice\Loader;
use League\Tactician\CommandBus;
use League\Tactician\Exception\MissingHandlerException;
use Nelmio\Alice\Loader\Yaml;
use Nette\DI\Container;
use PHPUnit_Framework_Assert;
use PHPUnit_Framework_TestCase;
use stdClass;
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


	public function testSymfonyServiceReferencing()
	{
		/** @var CommandBus $commandBus */
		$commandBus = $this->container->getByType(CommandBus::class);
		$this->assertInstanceOf(CommandBus::class, $commandBus);

		/** @var \Closure $middlewareChain */
		$middlewareChain = PHPUnit_Framework_Assert::getObjectAttribute($commandBus, 'middlewareChain');

		$this->setExpectedException(MissingHandlerException::class);
		$middlewareChain(new stdClass);
	}

}
