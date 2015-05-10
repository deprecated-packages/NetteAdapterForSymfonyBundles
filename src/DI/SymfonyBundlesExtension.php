<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension\DI;

use Nette\DI\CompilerExtension;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symnedi\SymfonyBundlesExtension\Compiler\FakeReferencesPass;
use Symnedi\SymfonyBundlesExtension\Contract\DefinitionExtractorInterface;
use Symnedi\SymfonyBundlesExtension\Contract\NetteServiceDefinitionFactoryInterface;
use Symnedi\SymfonyBundlesExtension\DefinitionExtractor;
use Symnedi\SymfonyBundlesExtension\NetteServiceDefinitionFactory;
use Symnedi\SymfonyBundlesExtension\SymfonyContainerAdapter;


class SymfonyBundlesExtension extends CompilerExtension
{

	/**
	 * @var DefinitionExtractorInterface
	 */
	private $definitionExtractor;

	/**
	 * @var NetteServiceDefinitionFactoryInterface
	 */
	private $netteServiceDefinitionFactory;


	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$bundles = (array) $this->getConfig();

		$serviceDefinitions = $this->getDefinitionExtractor()->extractFromBundles($bundles);
		foreach ($serviceDefinitions as $name => $serviceDefinition) {
			$builder->addDefinition(
				$this->persistUniqueName($name),
				$this->getNetteServiceDefinitionFactory()->create($serviceDefinition)
			);
		}

		$this->addSymfonyContainerAdapter($builder);
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
	 * @return DefinitionExtractorInterface
	 */
	private function getDefinitionExtractor()
	{
		if ($this->definitionExtractor === NULL) {
			$containerBuilder = new ContainerBuilder;
			$containerBuilder->addCompilerPass(new FakeReferencesPass, PassConfig::TYPE_BEFORE_OPTIMIZATION);
			$this->definitionExtractor = new DefinitionExtractor($containerBuilder);
		}
		return $this->definitionExtractor;
	}


	/**
	 * @return NetteServiceDefinitionFactoryInterface
	 */
	private function getNetteServiceDefinitionFactory()
	{
		if ($this->netteServiceDefinitionFactory === NULL) {
			$this->netteServiceDefinitionFactory = new NetteServiceDefinitionFactory;
		}
		return $this->netteServiceDefinitionFactory;
	}


	private function addSymfonyContainerAdapter()
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition('service_container')
			->setClass(SymfonyContainerAdapter::class);
	}

}
