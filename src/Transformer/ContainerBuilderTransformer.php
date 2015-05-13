<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension\Transformer;

use Nette\DI\ContainerBuilder as NetteContainerBuilder;
use Nette\Utils\Strings;
use ReflectionClass;
use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symnedi\SymfonyBundlesExtension\Utils\Naming;


class ContainerBuilderTransformer
{

	/**
	 * @var ServiceDefinitionTransformer
	 */
	private $serviceDefinitionTransformer;


	public function __construct(NetteContainerBuilder $netteContainerBuilder)
	{
		$this->serviceDefinitionTransformer = new ServiceDefinitionTransformer($netteContainerBuilder);
	}


	public function transformFromNetteToSymfony(
		NetteContainerBuilder $netteContainerBuilder,
		SymfonyContainerBuilder $symfonyContainerBuilder
	) {
		$netteServiceDefinitions = $netteContainerBuilder->getDefinitions();

		$symfonyServiceDefinitions = [];
		foreach ($netteServiceDefinitions as $name => $serviceDefinition) {
			$symfonyServiceDefinitions[$name] = $this->serviceDefinitionTransformer->transformFromNetteToSymfony(
				$serviceDefinition
			);
		}

		$symfonyContainerBuilder->addDefinitions($symfonyServiceDefinitions);
	}


	public function transformFromSymfonyToNette(
		SymfonyContainerBuilder $symfonyContainerBuilder,
		NetteContainerBuilder $netteContainerBuilder
	) {
		$symfonyServiceDefinitions = $symfonyContainerBuilder->getDefinitions();

		foreach ($symfonyServiceDefinitions as $name => $symfonyServiceDefinition) {
			$class = $this->determineClass($name, $symfonyServiceDefinition);
			$name = Naming::sanitazeClassName($name);
			if ( ! $netteContainerBuilder->getByType($class)) {
				$netteContainerBuilder->addDefinition(
					$name, $this->serviceDefinitionTransformer->transformFromSymfonyToNette($symfonyServiceDefinition)
				);
			}
		}
	}


	/**
	 * @param string $name
	 * @param Definition $symfonyServiceDefinition
	 * @return string
	 */
	private function determineClass($name, Definition $symfonyServiceDefinition)
	{
		$class = $symfonyServiceDefinition->getClass();
		if (class_exists($name)) {
			$class = (new ReflectionClass($name))->getName();
		}
		return $class;
	}

}
