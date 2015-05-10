<?php

namespace Symnedi\SymfonyBundlesExtension\Tests;

use Doctrine\Common\Collections\ArrayCollection;
use Hautelook\AliceBundle\Alice\Loader;
use League\Tactician\CommandBus;
use Nelmio\Alice\LoaderInterface;
use Nette\DI\Container;
use PHPUnit_Framework_Assert;
use PHPUnit_Framework_TestCase;
use Symnedi\SymfonyBundlesExtension\Tests\ContainerSource\AutowiredService;


class ContainerTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Container
	 */
	private $container;


	public function __construct()
	{
		$this->container = (new ContainerFactory)->create();
	}


	public function testFetchingService()
	{
		$loader = $this->container->getByType(Loader::class);
		$this->assertInstanceOf(Loader::class, $loader);

		/** @var Loader $loader */
		$loaders = PHPUnit_Framework_Assert::getObjectAttribute($loader, 'loaders');
		$this->assertCount(1, $loaders);
		$this->assertArrayHasKey('yaml', $loaders);
		$this->assertInstanceOf(LoaderInterface::class, $loaders['yaml']);

		$this->assertInstanceOf(ArrayCollection::class, $loader->getReferences());
	}


	public function testReferenceToOtherService()
	{
		$commandBus = $this->container->getByType(CommandBus::class);
		$this->assertInstanceOf(CommandBus::class, $commandBus);
	}


	public function testAutowiredService()
	{
		/** @var AutowiredService $autowiredService */
		$autowiredService = $this->container->getByType(AutowiredService::class);
		$this->assertInstanceOf(Loader::class, $autowiredService->getLoader());
	}

}
