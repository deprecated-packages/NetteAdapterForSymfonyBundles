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


	public function setContainerBuilder(NetteContainerBuilder $netteContainerBuilder)
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
				$definition = $argument;

				// todo: duplicate to ServiceDefinitionTransformer logic
				$name = Naming::sanitazeClassName($definition->getClass());
				$netteServiceDefinition = $this->netteContainerBuilder->addDefinition($name)
					->setClass($definition->getClass())
					->setArguments($this->transformFromSymfonyToNette($definition->getArguments()))
					->setTags($definition->getTags());

				foreach ($definition->getMethodCalls() as $methodCall) {
					$methodCallArguments = $this->transformFromSymfonyToNette($methodCall[1]);
					$netteServiceDefinition->addSetup($methodCall[0], $methodCallArguments);
				}

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
