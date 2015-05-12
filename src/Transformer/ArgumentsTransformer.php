<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension\Transformer;

use Nette\DI\ContainerBuilder as NetteContainerBuilder;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Reference;


class ArgumentsTransformer
{

	/**
	 * @var NetteContainerBuilder
	 */
	private $netteContainerBuilder;


	public function __construct(NetteContainerBuilder $netteContainerBuilder)
	{
		$this->netteContainerBuilder = $netteContainerBuilder;
	}


	/**
	 * @return array
	 */
	public function transformFromSymfonyToNette(array $arguments)
	{
		foreach ($arguments as $key => $argument) {
			if ($argument instanceof Reference) {
				$arguments[$key] = $this->determineServiceName($argument);

			} elseif (is_array($argument)) {
				$arguments[$key] = $this->transformFromSymfonyToNette($argument);
			}
		}

		return $arguments;
	}


	/**
	 * @return string
	 */
	private function determineServiceName(Reference $argument)
	{
		$name = (string) $argument;
		if ($name[0] === '@') {
			$className = (new ReflectionClass(substr($name, 1)))->getName();
			$this->netteContainerBuilder->prepareClassList();
			$name = $this->netteContainerBuilder->getByType($className);
		}
		return '@' . $name;
	}

}
