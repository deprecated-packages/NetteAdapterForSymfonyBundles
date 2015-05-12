<?php

namespace Symnedi\SymfonyBundlesExtension\Tests;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\Alice\Loader;
use League\Tactician\Bundle\Handler\ContainerBasedHandlerLocator;
use League\Tactician\CommandBus;
use League\Tactician\Handler\Locator\HandlerLocator;
use Nelmio\Alice\LoaderInterface;
use Nette\DI\Container;
use PHPUnit_Framework_Assert;
use PHPUnit_Framework_TestCase;
use Symnedi\SymfonyBundlesExtension\Tests\ContainerSource\AutowiredService;
use Symnedi\SymfonyBundlesExtension\Tests\ContainerSource\EntityManager;


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

		$entityManager = $this->container->getByType(EntityManagerInterface::class);
		$this->assertInstanceOf(EntityManager::class, $entityManager);
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
		$this->assertInstanceOf(AutowiredService::class, $autowiredService);
		$this->assertInstanceOf(Loader::class, $autowiredService->getLoader());
	}


	public function testTaggedServices()
	{
		/** @var HandlerLocator $handlerLocator */
		$handlerLocator = $this->container->getByType(HandlerLocator::class);
		$this->assertInstanceOf(HandlerLocator::class, $handlerLocator);
		$this->assertInstanceOf(ContainerBasedHandlerLocator::class, $handlerLocator);

		$this->assertCount(1, PHPUnit_Framework_Assert::getObjectAttribute($handlerLocator, 'commandToServiceId'));
	}

}
