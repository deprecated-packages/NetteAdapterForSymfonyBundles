<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension\DI;

use Nette\DI\CompilerExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;
use Symnedi\SymfonyBundlesExtension\DefinitionExtractor;
use Symnedi\SymfonyBundlesExtension\DefinitionExtractorFactory;
use Symnedi\SymfonyBundlesExtension\SymfonyContainerAdapter;
use Symnedi\SymfonyBundlesExtension\Transformer\ServiceDefinitionTransformer;


class SymfonyBundlesExtension extends CompilerExtension
{

	/**
	 * @var DefinitionExtractor
	 */
	private $definitionExtractor;

	/**
	 * @var ServiceDefinitionTransformer
	 */
	private $serviceDefinitionTransformer;

	/**
	 * @var SymfonyContainerBuilder
	 */
	private $symfonyContainerBuilder;



	public function __construct()
	{
		$this->symfonyContainerBuilder = new SymfonyContainerBuilder;
		$this->serviceDefinitionTransformer = new ServiceDefinitionTransformer;
	}


	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$bundles = (array) $this->getConfig();

		$serviceDefinitions = $this->getDefinitionExtractor()->extractFromBundles($bundles);
		foreach ($serviceDefinitions as $name => $serviceDefinition) {
			$builder->addDefinition(
				$this->persistUniqueName($name),
				$this->serviceDefinitionTransformer->transformFromSymfonyToNette($serviceDefinition)
			);
		}

		$this->addSymfonyContainerAdapter($builder);
	}


	/**
	 * Process tagged services etc.
	 */
	public function beforeCompile()
	{
	}


	/**
	 * @param string $name
	 * @return string
	 */
	private function persistUniqueName($name)
	{
		if ($this->getContainerBuilder()->hasDefinition($name)) {
			$name = $this->prefix($name);
		}
		return $name;
	}


	/**
	 * @return DefinitionExtractor
	 */
	private function getDefinitionExtractor()
	{
		if ($this->definitionExtractor === NULL) {
			$this->definitionExtractor = (new DefinitionExtractorFactory())->create(
				$this->getContainerBuilder(), $this->symfonyContainerBuilder
			);
		}
		return $this->definitionExtractor;
	}


	private function addSymfonyContainerAdapter()
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition('service_container') // name of Symfony container service
			->setClass(SymfonyContainerAdapter::class);
	}

}
