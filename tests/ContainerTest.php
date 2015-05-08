<?php

namespace Symnedi\SymfonyBundlesExtension\Tests;

use Doctrine\Common\Collections\ArrayCollection;
use Hautelook\AliceBundle\Alice\Loader;
use Nette\Configurator;
use Nette\DI\Container;
use PHPUnit_Framework_TestCase;


class ContainerTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Container
	 */
	private $container;


	public function __construct()
	{
		$this->container = $this->createContainer();
	}


	public function testFetchingService()
	{
		$loader = $this->container->getByType(Loader::class);
		$this->assertInstanceOf(Loader::class, $loader);

		/** @var Loader $loader */
		$this->assertInstanceOf(ArrayCollection::class, $loader->getReferences());
	}


	/**
	 * @return Container
	 */
	private function createContainer()
	{
		$configurator = new Configurator;
		$configurator->addConfig(__DIR__ . '/config/default.neon');
		$configurator->setTempDirectory(TEMP_DIR);
		return $configurator->createContainer();
	}

}
