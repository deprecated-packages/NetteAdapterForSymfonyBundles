<?php

namespace Symnedi\SymfonyBundlesExtension\Tests\Container;

use Doctrine\ORM\EntityManager;
use Nette\DI\Container;
use PHPUnit_Framework_TestCase;
use Symnedi\SymfonyBundlesExtension\DI\SymfonyBundlesExtension;
use Symnedi\SymfonyBundlesExtension\SymfonyContainerAdapter;
use Symnedi\SymfonyBundlesExtension\Tests\ContainerFactory;


class BundleParametersTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Container
	 */
	private $container;


	public function __construct()
	{
		$this->container = (new ContainerFactory)->createWithConfig(
			__DIR__ . '/SymfonyContainerAdapterSource/config/init.neon'
		);
	}


	public function testBundleParameters()
	{
		$symfonyContainerAdapter = $this->getSymfonyContainerAdapter();
		$this->assertTrue($symfonyContainerAdapter->hasParameter('doctrine.orm.proxy_namespace'));
	}


	/**
	 * @return SymfonyContainerAdapter
	 */
	private function getSymfonyContainerAdapter()
	{
		return $this->container->getService(
			SymfonyBundlesExtension::SYMFONY_CONTAINER_SERVICE_NAME
		);
	}

}
