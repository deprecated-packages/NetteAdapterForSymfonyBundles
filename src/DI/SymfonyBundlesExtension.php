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


	public function __construct()
	{
		$this->symfonyContainerBuilder = new SymfonyContainerBuilder;
		$this->symfonyContainerBuilder->addCompilerPass(new FakeReferencesPass);
		$this->containerBuilderTransformer = new ContainerBuilderTransformer;
	}


	public function loadConfiguration()
	{
		$bundles = (array) $this->getConfig();
		$this->loadBundlesToSymfonyContainerBuilder($bundles);
	}


	public function beforeCompile()
	{
		$this->containerBuilderTransformer->transformFromNetteToSymfony(
			$this->getContainerBuilder(), $this->symfonyContainerBuilder
		);

		$this->addSymfonyContainerAdapter();
		$this->symfonyContainerBuilder->compile();

		$this->containerBuilderTransformer->transformFromSymfonyToNette(
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
	 */
	private function loadBundlesToSymfonyContainerBuilder(array $bundles)
	{
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

}
