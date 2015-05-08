<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symnedi\SymfonyBundlesExtension\Contract\DefinitionExtractorInterface;


class DefinitionExtractor implements DefinitionExtractorInterface
{

	/**
	 * {@inheritdoc}
	 */
	public function extractFromBundles($bundles)
	{
		$serviceDefinitions = [];
		foreach ($bundles as $bundleClass) {
			if (class_exists($bundleClass)) {
				/** @var Bundle $bundle */
				$bundle = new $bundleClass;
				$serviceDefinitions += $this->extractFromBundle($bundle);
			}
		}
		return $serviceDefinitions;
	}


	/**
	 * {@inheritdoc}
	 */
	public function extractFromBundle(Bundle $bundle)
	{
		return $this->extractFromExtension($bundle->getContainerExtension());
	}


	/**
	 * {@inheritdoc}
	 */
	public function extractFromExtension(ExtensionInterface $extension)
	{
		$symfonyContainerBuilder = new ContainerBuilder;
		$symfonyContainerBuilder->registerExtension($extension);
		$symfonyContainerBuilder->loadFromExtension($extension->getAlias());
		$symfonyContainerBuilder->compile();
		return $symfonyContainerBuilder->getDefinitions();
	}

}
