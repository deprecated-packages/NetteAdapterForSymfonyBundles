<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension;

use Nette\DI\ServiceDefinition;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symnedi\SymfonyBundlesExtension\Contract\NetteServiceDefinitionFactoryInterface;


class NetteServiceDefinitionFactory implements NetteServiceDefinitionFactoryInterface
{

	/**
	 * {@inheritdoc}
	 */
	public function create(Definition $definition)
	{
		$newDefinition = (new ServiceDefinition)
			->setClass($definition->getClass())
			->setArguments($this->processArguments($definition->getArguments()))
			->setTags($definition->getTags());

		return $newDefinition;
	}


	private function processArguments(array $arguments)
	{
		foreach ($arguments as $key => $argument) {
			if ($argument instanceof Reference) {
				$arguments[$key] = '@' . (string) $argument;

			} elseif (is_array($argument)) {
				$arguments[$key] = $this->processArguments($argument);
			}
		}

		return $arguments;
	}

}
