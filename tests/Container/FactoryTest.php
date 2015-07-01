<?php

namespace Symnedi\SymfonyBundlesExtension\Tests\Container;

use Doctrine\ORM\EntityManager;
use Nette\DI\Container;
use PHPUnit_Framework_TestCase;
use Symnedi\SymfonyBundlesExtension\Tests\ContainerFactory;


class FactoryTest extends PHPUnit_Framework_TestCase
{

	public function testFactory()
	{
		$container = (new ContainerFactory)->createWithConfig(__DIR__ . '/FactorySource/config/init.neon');

		/** @var EntityManager $entityManager */
		$entityManager = $container->getByType(EntityManager::class);
		$this->assertInstanceOf(EntityManager::class, $entityManager);
	}

}
