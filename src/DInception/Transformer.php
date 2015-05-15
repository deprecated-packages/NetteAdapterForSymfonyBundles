<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension\DInception;

use Nette\Configurator;
use Nette\DI\Container;


class Transformer
{

	/**
	 * @return Container
	 */
	public function create()
	{
		$configurator = new Configurator;
		$configurator->addConfig(__DIR__ . '/services.neon');
		$configurator->setTempDirectory(sys_get_temp_dir() . '/_symnedi/' . getmypid());
		return $configurator->createContainer();
	}

}
