<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension\Transformer;

use Nette\DI\ContainerBuilder as NetteContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;


class ContainerBuilderTransformer
{

	public function transformFromSymfonyToNette(SymfonyContainerBuilder $symfonyContainerBuilder)
	{
	}


	/**
	 * @return SymfonyContainerBuilder
	 */
	public function transformFromNetteToSymfony(
		NetteContainerBuilder $netteContainerBuilder, SymfonyContainerBuilder $symfonyContainerBuilder
	) {
//		$netteContainerBuilder->prepareClassList();
//		var_dump($netteContainerBuilder->findByTag('tactician.handler'));
		return $symfonyContainerBuilder;
	}

}
