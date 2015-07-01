<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension\Transformer;

use Nette\DI\ServiceDefinition;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;


class ServiceDefinitionTransformer
{

	/**
	 * @var ArgumentsTransformer
	 */
	private $argumentsTransformer;


	public function __construct(ArgumentsTransformer $argumentsTransformer)
	{
		$this->argumentsTransformer = $argumentsTransformer;
	}


	public function transformFromSymfonyToNette(Definition $symfonyDefinition)
	{
		// # Transformer in chain?
		// 1. transform definitions
		// 2. transform arguments
		$arguments = $this->argumentsTransformer->transformFromSymfonyToNette($symfonyDefinition->getArguments());

		$netteDefinition = (new ServiceDefinition)
			->setClass($symfonyDefinition->getClass())
			->setTags($symfonyDefinition->getTags());

		foreach ($symfonyDefinition->getMethodCalls() as $methodCall) {
			$methodCallArguments = $this->argumentsTransformer->transformFromSymfonyToNette($methodCall[1]);
			$netteDefinition->addSetup($methodCall[0], $methodCallArguments);
		}

		// todo: methodCalls <=> setup

		if ($factory = $symfonyDefinition->getFactory()) {
			if (is_array($factory) && $factory[0] instanceof Reference) {
				$serviceReference = $factory[0];
				$createMethod = $factory[1];

				// note: possible issue - static vs dynamic?
				$factory = ['@' . $serviceReference, $createMethod];
			}

			$netteDefinition->setFactory($factory, $arguments);

		} else {
			$netteDefinition->setArguments($arguments);
		}

		return $netteDefinition;
	}


	public function transformFromNetteToSymfony(ServiceDefinition $netteDefinition)
	{
		$symfonyDefinition = (new Definition)
			->setClass($netteDefinition->getClass())
			->setTags($netteDefinition->getTags());

		if ($netteDefinition->getFactory()) {
			$factory = $netteDefinition->getFactory();
			$symfonyDefinition->setFactory($factory->getEntity());
			$symfonyDefinition->setArguments($factory->arguments);
		}

		return $symfonyDefinition;
	}

}
