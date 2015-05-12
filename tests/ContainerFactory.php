<?php

namespace Symnedi\SymfonyBundlesExtension\Tests;

use Nette\Configurator;
use Nette\DI\Container;


class ContainerFactory
{

	/**
	 * @return Container
	 */
	public function create()
	{
		return $this->createWithConfig(__DIR__ . '/config/default.neon');
	}


	/**
	 * @return Container
	 */
	public function createWithConfig($config)
	{
		$configurator = new Configurator;
		$configurator->addConfig($config);
		$configurator->setTempDirectory(TEMP_DIR);
		return $configurator->createContainer();
	}

}
