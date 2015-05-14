<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension\Transformer;

use Nette\DI\ContainerBuilder as NetteContainerBuilder;
use Nette\DI\ServiceDefinition;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;


class ServiceDefinitionTransformer
{

	/**
	 * @var ArgumentsTransformer
	 */
	private $argumentsTransformer;


	public function __construct(NetteContainerBuilder $netteContainerBuilder)
	{
		$this->argumentsTransformer = new ArgumentsTransformer($netteContainerBuilder);
	}


	public function transformFromSymfonyToNette(Definition $symfonyDefinition)
	{
		$arguments = $this->argumentsTransformer->transformFromSymfonyToNette($symfonyDefinition->getArguments());

		$netteDefinition = (new ServiceDefinition)
			->setClass($symfonyDefinition->getClass())
			->setTags($symfonyDefinition->getTags());

		if ($factory = $symfonyDefinition->getFactory()) {
			if (is_array($factory) && $factory[0] instanceof Reference) {
				$serviceReference = $factory[0];
				$createMethod = $factory[1];

				// note: static vs dynamic?
//				$factory = '@' . $serviceReference . '::' . $createMethod;
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
			$symfonyDefinition->setArguments($netteDefinition->getFactory()->arguments);
		}

		return $symfonyDefinition;
	}

}
