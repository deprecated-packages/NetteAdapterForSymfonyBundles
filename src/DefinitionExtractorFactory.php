<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension;

use Nette\DI\ContainerBuilder as NetteContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;
use Symnedi\SymfonyBundlesExtension\Compiler\FakeReferencesPass;
use Symnedi\SymfonyBundlesExtension\Transformer\ContainerBuilderTransformer;


class DefinitionExtractorFactory
{

	public function __construct()
	{
		$this->containerBuilderTransformer = new ContainerBuilderTransformer;
	}


	/**
	 * @param NetteContainerBuilder $netteContainerBuilder
	 * @param SymfonyContainerBuilder $symfonyContainerBuilder
	 * @return DefinitionExtractor
	 */
	public function create(NetteContainerBuilder $netteContainerBuilder, SymfonyContainerBuilder $symfonyContainerBuilder)
	{
		$symfonyContainerBuilder = $this->containerBuilderTransformer->transformFromNetteToSymfony(
			$netteContainerBuilder, $symfonyContainerBuilder
		);
		$symfonyContainerBuilder->addCompilerPass(new FakeReferencesPass);
		return new DefinitionExtractor($symfonyContainerBuilder);
	}

}
