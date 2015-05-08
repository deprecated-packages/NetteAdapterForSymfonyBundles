<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension;

use Nette\DI\ServiceDefinition;
use Symfony\Component\DependencyInjection\Definition;
use Symnedi\SymfonyBundlesExtension\Contract\NetteServiceDefinitionFactoryInterface;


class NetteServiceDefinitionFactory implements NetteServiceDefinitionFactoryInterface
{

	/**
	 * {@inheritdoc}
	 */
	public function create(Definition $definition)
	{
		$newDefinition = (new ServiceDefinition);
		$newDefinition->setClass($definition->getClass());
		$newDefinition->setArguments($definition->getArguments());
		$newDefinition->setTags($definition->getTags());
		return $newDefinition;
	}

}
