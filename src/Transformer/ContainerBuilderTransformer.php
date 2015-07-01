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


	public function __construct(ServiceDefinitionTransformer $serviceDefinitionTransformer)
	{
		$this->serviceDefinitionTransformer = $serviceDefinitionTransformer;
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

			if ($this->canServiceBeAdded($netteContainerBuilder, $name, $class)) {
				$netteContainerBuilder->addDefinition(
					$name,
					$this->serviceDefinitionTransformer->transformFromSymfonyToNette($symfonyServiceDefinition)
				);
			}
		}

		$this->transformParametersFromSymfonyToNette($symfonyContainerBuilder, $netteContainerBuilder);
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


	private function transformParametersFromSymfonyToNette(
		SymfonyContainerBuilder $symfonyContainerBuilder,
		NetteContainerBuilder $netteContainerBuilder
	) {
		// transform parameters
		$parameterBag = $symfonyContainerBuilder->getParameterBag();
		foreach ($parameterBag->all() as $key => $value) {
			$netteContainerBuilder->parameters[$key] = $value;
		}
	}


	/**
	 * @param NetteContainerBuilder $netteContainerBuilder
	 * @param string $name
	 * @param string $class
	 * @return bool
	 */
	private function canServiceBeAdded(NetteContainerBuilder $netteContainerBuilder, $name, $class)
	{
		if ($netteContainerBuilder->hasDefinition($name)) {
			return FALSE;
		}

		if ($netteContainerBuilder->getByType($class)) {
			return FALSE;
		}

		return TRUE;
	}

}
