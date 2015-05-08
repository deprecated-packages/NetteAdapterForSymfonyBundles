<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension\Contract;

use Nette\DI\ServiceDefinition;
use Symfony\Component\DependencyInjection\Definition;


interface NetteServiceDefinitionFactoryInterface
{

	/**
	 * @return ServiceDefinition
	 */
	function create(Definition $definition);

}
