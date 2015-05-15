<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension\DInception;

use Nette\Configurator;
use Nette\DI\Container;
use Nette\DI\ContainerBuilder;
use Symnedi\SymfonyBundlesExtension\Transformer\ArgumentsTransformer;


class Transformer
{

	/**
	 * @var ContainerBuilder
	 */
	private $containerBuilder;

	/**
	 * @var string
	 */
	private $tempDir;


	/**
	 * @param ContainerBuilder $containerBuilder
	 * @param string $tempDir
	 */
	public function __construct(ContainerBuilder $containerBuilder, $tempDir)
	{
		$this->containerBuilder = $containerBuilder;
		$this->tempDir = $tempDir;
	}


	/**
	 * @return Container
	 */
	public function create()
	{
		$configurator = new Configurator;
		$configurator->addConfig(__DIR__ . '/services.neon');
		$configurator->setTempDirectory($this->tempDir);
		$container = $configurator->createContainer();

		/** @var ArgumentsTransformer $argumentsTransformer */
		$argumentsTransformer = $container->getByType(ArgumentsTransformer::class);
		$argumentsTransformer->setContainerBuilder($this->containerBuilder);

		return $container;
	}

}
