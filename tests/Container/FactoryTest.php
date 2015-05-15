<?php

namespace Symnedi\SymfonyBundlesExtension\Tests\Container;

use Doctrine\ORM\EntityManager;
use Nette\DI\Container;
use PHPUnit_Framework_TestCase;
use Symnedi\SymfonyBundlesExtension\Tests\ContainerFactory;


class FactoryTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Container
	 */
	private $container;


	public function __construct()
	{
		$this->container = (new ContainerFactory)->createWithConfig(__DIR__ . '/FactorySource/config/init.neon');
	}


	public function testFactory()
	{
		/** @var EntityManager $entityManager */
		$entityManager = $this->container->getByType(EntityManager::class);
		$this->assertInstanceOf(EntityManager::class, $entityManager);
	}

}
