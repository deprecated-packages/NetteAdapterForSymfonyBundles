<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension\Transformer;

use Nette\DI\ContainerBuilder as NetteContainerBuilder;
use Nette\DI\ServiceDefinition;
use Symfony\Component\DependencyInjection\Definition;


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
		$netteDefinition = (new ServiceDefinition)
			->setClass($symfonyDefinition->getClass())
			->setArguments($this->argumentsTransformer->transformFromSymfonyToNette($symfonyDefinition->getArguments()))
			->setTags($symfonyDefinition->getTags());

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
