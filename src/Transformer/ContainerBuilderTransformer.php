<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension\Transformer;

use Nette\DI\ContainerBuilder as NetteContainerBuilder;
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

		foreach ($netteServiceDefinitions as $name => $netteServiceDefinition) {
			$symfonyServiceDefinition = $this->serviceDefinitionTransformer->transformFromNetteToSymfony(
				$netteServiceDefinition
			);
			$symfonyContainerBuilder->setDefinition($name, $symfonyServiceDefinition);
		}
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
					$name,
					$this->serviceDefinitionTransformer->transformFromSymfonyToNette($symfonyServiceDefinition)
				);
			}
		}

		$this->transformParamtersFromSymfonyToNette($symfonyContainerBuilder, $netteContainerBuilder);
	}


	/**
	 * @param string $name
	 * @param Definition $symfonyServiceDefinition
	 * @return string
	 */
	private function determineClass($name, Definition $symfonyServiceDefinition)
	{
		if (class_exists($name)) {
			return (new ReflectionClass($name))->getName();

		} else {
			return $symfonyServiceDefinition->getClass();
		}
	}


	private function transformParamtersFromSymfonyToNette(
		SymfonyContainerBuilder$symfonyContainerBuilder,
		NetteContainerBuilder $netteContainerBuilder
	) {
		// transform parameters
		$parameterBag = $symfonyContainerBuilder->getParameterBag();
		foreach ($parameterBag->all() as $key => $value) {
			$netteContainerBuilder->parameters[$key] = $value;
		}
	}

}
