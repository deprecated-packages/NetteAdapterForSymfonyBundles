<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension\DI;

use Nette\DI\CompilerExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symnedi\SymfonyBundlesExtension\Compiler\FakeReferencesPass;
use Symnedi\SymfonyBundlesExtension\SymfonyContainerAdapter;
use Symnedi\SymfonyBundlesExtension\Transformer\ServiceDefinitionTransformer;


class SymfonyBundlesExtension extends CompilerExtension
{

	/**
	 * @var SymfonyContainerBuilder
	 */
	private $symfonyContainerBuilder;

	/**
	 * @var ServiceDefinitionTransformer
	 */
	private $serviceDefinitionTransformer;


	public function __construct()
	{
		$this->symfonyContainerBuilder = new SymfonyContainerBuilder;
		$this->symfonyContainerBuilder->addCompilerPass(new FakeReferencesPass);
		$this->serviceDefinitionTransformer = new ServiceDefinitionTransformer;
	}


	public function loadConfiguration()
	{
		$netteContainerBuilder = $this->getContainerBuilder();
		$bundles = (array) $this->getConfig();

		foreach ($bundles as $bundleClass) {
			/** @var Bundle $bundle */
			$bundle = new $bundleClass;
			if ($extension = $bundle->getContainerExtension()) {
				$this->symfonyContainerBuilder->registerExtension($extension);
				$this->symfonyContainerBuilder->loadFromExtension($extension->getAlias());
			}
			$bundle->build($this->symfonyContainerBuilder);
		}

		$this->symfonyContainerBuilder->compile();
		$serviceDefinitions = $this->symfonyContainerBuilder->getDefinitions();
		foreach ($serviceDefinitions as $name => $serviceDefinition) {
			$netteContainerBuilder->addDefinition(
				$this->persistUniqueName($name),
				$this->serviceDefinitionTransformer->transformFromSymfonyToNette($serviceDefinition)
			);
		}

		$this->addSymfonyContainerAdapter($netteContainerBuilder);
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


	private function addSymfonyContainerAdapter()
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition('service_container') // name of Symfony container service
			->setClass(SymfonyContainerAdapter::class);
	}

}
