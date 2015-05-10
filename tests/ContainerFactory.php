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
		$configurator = new Configurator;
		$configurator->addConfig(__DIR__ . '/config/default.neon');
		$configurator->setTempDirectory(TEMP_DIR);
		return $configurator->createContainer();
	}

}
