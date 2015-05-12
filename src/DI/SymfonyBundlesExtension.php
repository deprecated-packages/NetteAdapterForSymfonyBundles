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
use Symnedi\SymfonyBundlesExtension\Transformer\ContainerBuilderTransformer;


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
	 * @var ContainerBuilderTransformer
	 */
	private $containerBuilderTransformer;

	/**
	 * @var array
	 */
	private $defaults = [
		'bundles' => [],
		'parameters' => []
	];


	public function __construct()
	{
		$this->symfonyContainerBuilder = new SymfonyContainerBuilder;
		$this->symfonyContainerBuilder->addCompilerPass(new FakeReferencesPass);
	}


	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);
		$this->loadBundlesToSymfonyContainerBuilder($config['bundles'], $config['parameters']);
	}


	public function beforeCompile()
	{
		$this->getContainerBuilderTransformer()->transformFromNetteToSymfony(
			$this->getContainerBuilder(), $this->symfonyContainerBuilder
		);

		$this->addSymfonyContainerAdapter();
		$this->symfonyContainerBuilder->compile();

		$this->getContainerBuilderTransformer()->transformFromSymfonyToNette(
			$this->symfonyContainerBuilder, $this->getContainerBuilder()
		);
	}


	private function addSymfonyContainerAdapter()
	{
		$this->getContainerBuilder()->addDefinition(self::SYMFONY_CONTAINER_SERVICE_NAME)
			->setClass(SymfonyContainerAdapter::class);
	}


	/**
	 * @param string[] $bundles
	 * @param array[] $parameters
	 */
	private function loadBundlesToSymfonyContainerBuilder(array $bundles, array $parameters)
	{
		foreach ($bundles as $name => $bundleClass) {
			/** @var Bundle $bundle */
			$bundle = new $bundleClass;
			if ($extension = $bundle->getContainerExtension()) {
				$this->symfonyContainerBuilder->registerExtension($extension);
				$this->symfonyContainerBuilder->loadFromExtension(
					$extension->getAlias(),
					isset($parameters[$name]) ? $parameters[$name] : []
				);
			}
			$bundle->build($this->symfonyContainerBuilder);
		}
	}


	/**
	 * @return ContainerBuilderTransformer
	 */
	private function getContainerBuilderTransformer()
	{
		if ($this->containerBuilderTransformer === NULL) {
			$this->containerBuilderTransformer = new ContainerBuilderTransformer($this->getContainerBuilder());
		}
		return $this->containerBuilderTransformer;
	}

}
