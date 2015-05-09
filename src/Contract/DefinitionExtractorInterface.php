<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension\Contract;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Bundle\Bundle;


interface DefinitionExtractorInterface
{

	/**
	 * @param Bundle[] $bundles
	 * @return Definition[]
	 */
	function extractFromBundles($bundles);

}
