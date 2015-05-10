<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Bundle\Bundle;


class DefinitionExtractor
{

	/**
	 * @var ContainerBuilder
	 */
	private $containerBuilder;


	public function __construct(ContainerBuilder $containerBuilder)
	{
		$this->containerBuilder = $containerBuilder;
	}


	/**
	 * @param Bundle[] $bundles
	 * @return Definition[]
	 */
	public function extractFromBundles($bundles)
	{
		foreach ($bundles as $bundleClass) {
			if (class_exists($bundleClass)) {
				/** @var Bundle $bundle */
				$bundle = new $bundleClass;
				$this->registerBundle($bundle);
				$bundle->build($this->containerBuilder);
			}
		}

		$this->containerBuilder->compile();
		return $this->containerBuilder->getDefinitions();
	}


	private function registerBundle(Bundle $bundle)
	{
		if ($extension = $bundle->getContainerExtension()) {
			$this->containerBuilder->registerExtension($extension);
			$this->containerBuilder->loadFromExtension($extension->getAlias());
		}
	}

}
