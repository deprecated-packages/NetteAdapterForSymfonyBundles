<?php

namespace Symnedi\SymfonyBundlesExtension\Tests\DoctrineBundle;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Nette\DI\Container;
use PHPUnit_Framework_TestCase;
use Symnedi\SymfonyBundlesExtension\Tests\ContainerFactory;


class InitTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Container
	 */
	private $container;


	public function __construct()
	{
		$this->container = (new ContainerFactory)->createWithConfig(__DIR__ . '/config/init.neon');
	}


	public function testGetService()
	{
		/** @var EntityManagerInterface $entityManager */
		$entityManager = $this->container->getByType(EntityManager::class);
		$this->assertInstanceOf(EntityManagerInterface::class, $entityManager);
	}

}
