<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\ServiceDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symnedi\SymfonyBundlesExtension\Compiler\FakeReferencesPass;
use Symnedi\SymfonyBundlesExtension\SymfonyContainerAdapter;
use Symnedi\SymfonyBundlesExtension\Transformer\ServiceDefinitionTransformer;


class SymfonyBundlesExtension extends CompilerExtension
{

	/**
	 * @var string
	 */
	const SYMFONY_CONTAINER_SERVICE_NAME = 'service_container';

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

	}


	/**
	 * Process tagged services etc.
	 */
	public function beforeCompile()
	{
		$netteContainerBuilder = $this->getContainerBuilder();

		$symfonyServiceDefinitions = array_map(function (ServiceDefinition $serviceDefinition) {
			return $this->serviceDefinitionTransformer->transformFromNetteToSymfony($serviceDefinition);
		}, $netteContainerBuilder->getDefinitions());
		$this->symfonyContainerBuilder->addDefinitions($symfonyServiceDefinitions);

		$this->addSymfonyContainerAdapter($netteContainerBuilder);
		$this->symfonyContainerBuilder->compile();

		$serviceDefinitions = $this->symfonyContainerBuilder->getDefinitions();

		foreach ($serviceDefinitions as $name => $serviceDefinition) {
			$name = (string) $name;

			if ( ! $netteContainerBuilder->getByType($serviceDefinition->getClass())) {
				$netteContainerBuilder->addDefinition(
					$name, $this->serviceDefinitionTransformer->transformFromSymfonyToNette($serviceDefinition)
				);
			}
		}
	}


	private function addSymfonyContainerAdapter()
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition(self::SYMFONY_CONTAINER_SERVICE_NAME)
			->setClass(SymfonyContainerAdapter::class);
	}

}
