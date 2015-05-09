<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symnedi\SymfonyBundlesExtension\Contract\DefinitionExtractorInterface;


class DefinitionExtractor implements DefinitionExtractorInterface
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
	 * {@inheritdoc}
	 */
	public function extractFromBundles($bundles)
	{
		foreach ($bundles as $bundleClass) {
			if (class_exists($bundleClass)) {
				/** @var Bundle $bundle */
				$bundle = new $bundleClass;
				$this->registerBundle($bundle);
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
		return [];
	}

}
