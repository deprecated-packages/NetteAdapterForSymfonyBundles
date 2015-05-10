<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension\DI;

use Nette\DI\CompilerExtension;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symnedi\SymfonyBundlesExtension\Compiler\FakeReferencesPass;
use Symnedi\SymfonyBundlesExtension\DefinitionExtractor;
use Symnedi\SymfonyBundlesExtension\SymfonyContainerAdapter;
use Symnedi\SymfonyBundlesExtension\Transformer\ContainerBuilderTransformer;
use Symnedi\SymfonyBundlesExtension\Transformer\ServiceDefinitionTransformer;


class SymfonyBundlesExtension extends CompilerExtension
{

	/**
	 * @var DefinitionExtractor
	 */
	private $definitionExtractor;

	/**
	 * @var ContainerBuilderTransformer
	 */
	private $containerBuilderTransformer;

	/**
	 * @var ServiceDefinitionTransformer
	 */
	private $serviceDefinitionTransformer;


	public function __construct()
	{
		$this->initTransformers();
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


//	/**
//	 * Process tagged services etc.
//	 */
//	public function beforeCompile()
//	{
//		$builder = $this->getContainerBuilder();

//		$serviceDefinitions = $this->definitionExtractor->extractFromBundles($bundles);
//		foreach ($serviceDefinitions as $name => $serviceDefinition) {
//			$builder->addDefinition(
//				$this->persistUniqueName($name),
//				$this->serviceDefinitionTransformer->transformFromSymfonyToNette($serviceDefinition)
//			);
//		}
//	}


	private function initTransformers()
	{
		$this->containerBuilderTransformer = new ContainerBuilderTransformer;
		$this->serviceDefinitionTransformer = new ServiceDefinitionTransformer;
		$this->containerBuilderTransformer = new ContainerBuilderTransformer;
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
//			$symfonyContainerBuilder = $this->containerBuilderTransformer->transformFromNetteToSymfony(
//				$this->getContainerBuilder()
//			);
			$symfonyContainerBuilder = new ContainerBuilder;
			$symfonyContainerBuilder->addCompilerPass(new FakeReferencesPass, PassConfig::TYPE_BEFORE_OPTIMIZATION);
			$this->definitionExtractor = new DefinitionExtractor($symfonyContainerBuilder);
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
