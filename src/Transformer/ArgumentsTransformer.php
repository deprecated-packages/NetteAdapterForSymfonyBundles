<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension\Transformer;

use Nette\DI\ContainerBuilder as NetteContainerBuilder;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symnedi\SymfonyBundlesExtension\Utils\Naming;


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

			} elseif ($argument instanceof Definition) {
				$name = Naming::sanitazeClassName($argument->getClass());
				$this->netteContainerBuilder->addDefinition($name)
					->setClass($argument->getClass())
					->setArguments($this->transformFromSymfonyToNette($argument->getArguments()))
					->setTags($argument->getTags());

				$arguments[$key] = '@' . $name;
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
