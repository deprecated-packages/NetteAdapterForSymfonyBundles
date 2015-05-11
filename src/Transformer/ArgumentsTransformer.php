<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension\Transformer;

use Symfony\Component\DependencyInjection\Reference;


class ArgumentsTransformer
{

	/**
	 * @return array
	 */
	public function transformFromSymfonyToNette(array $arguments)
	{
		foreach ($arguments as $key => $argument) {
			if ($argument instanceof Reference) {
				$arguments[$key] = '@' . (string) $argument;

			} elseif (is_array($argument)) {
				$arguments[$key] = $this->transformFromSymfonyToNette($argument);
			}
		}

		return $arguments;
	}

}
