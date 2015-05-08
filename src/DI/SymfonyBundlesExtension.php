<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension\DI;

use Nette\DI\CompilerExtension;
use Symnedi\SymfonyBundlesExtension\Contract\DefinitionExtractorInterface;
use Symnedi\SymfonyBundlesExtension\Contract\NetteServiceDefinitionFactoryInterface;
use Symnedi\SymfonyBundlesExtension\DefinitionExtractor;
use Symnedi\SymfonyBundlesExtension\NetteServiceDefinitionFactory;


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


	public function __construct()
	{
		$this->definitionExtractor = new DefinitionExtractor;
		$this->netteServiceDefinitionFactory = new NetteServiceDefinitionFactory;
	}


	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$bundles = (array) $this->getConfig();

		$serviceDefinitions = $this->definitionExtractor->extractFromBundles($bundles);
		foreach ($serviceDefinitions as $name => $serviceDefinition) {
			$builder->addDefinition(
				$this->persistUniqueName($name),
				$this->netteServiceDefinitionFactory->create($serviceDefinition)
			);
		}
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

}
