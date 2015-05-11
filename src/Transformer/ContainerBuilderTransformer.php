<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension\Transformer;

use Nette\DI\ContainerBuilder as NetteContainerBuilder;
use Nette\DI\ServiceDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;


class ContainerBuilderTransformer
{

	/**
	 * @var ServiceDefinitionTransformer
	 */
	private $serviceDefinitionTransformer;


	public function __construct()
	{
		$this->serviceDefinitionTransformer = new ServiceDefinitionTransformer;
	}


	/**
	 * @return SymfonyContainerBuilder
	 */
	public function transformFromNetteToSymfony(
		NetteContainerBuilder $netteContainerBuilder,
		SymfonyContainerBuilder $symfonyContainerBuilder
	) {
		$netteServiceDefinitions = $netteContainerBuilder->getDefinitions();

		$symfonyServiceDefinitions = array_map(function (ServiceDefinition $netteServiceDefinition) {
			return $this->serviceDefinitionTransformer->transformFromNetteToSymfony($netteServiceDefinition);
		}, $netteServiceDefinitions);

		$symfonyContainerBuilder->addDefinitions($symfonyServiceDefinitions);
		return $symfonyContainerBuilder;
	}


	public function transformFromSymfonyToNette(
		SymfonyContainerBuilder $symfonyContainerBuilder,
		NetteContainerBuilder $netteContainerBuilder
	) {
		$symfonyServiceDefinitions = $symfonyContainerBuilder->getDefinitions();

		foreach ($symfonyServiceDefinitions as $name => $symfonyServiceDefinition) {
			if ( ! $netteContainerBuilder->getByType($symfonyServiceDefinition->getClass())) {
				$netteContainerBuilder->addDefinition(
					$name, $this->serviceDefinitionTransformer->transformFromSymfonyToNette($symfonyServiceDefinition)
				);
			}
		}
	}

}
