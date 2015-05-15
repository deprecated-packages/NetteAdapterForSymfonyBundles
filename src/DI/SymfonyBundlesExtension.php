<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension\DI;

use Nette;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;
use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symnedi\SymfonyBundlesExtension\Compiler\FakeReferencesPass;
use Symnedi\SymfonyBundlesExtension\SymfonyContainerAdapter;
use Symnedi\SymfonyBundlesExtension\Transformer\ContainerBuilderTransformer;


class SymfonyBundlesExtension extends CompilerExtension
{

	/**
	 * @var string
	 */
	const SYMFONY_CONTAINER_SERVICE_NAME = 'service_container';

	/**
	 * @var SymfonyContainerBuilder
	 */
	private $symfonyContainerBuilder;

	/**
	 * @var ContainerBuilderTransformer
	 */
	private $containerBuilderTransformer;

	/**
	 * @var array[]
	 */
	private $defaults = [
		'bundles' => [],
		'parameters' => []
	];

	/**
	 * @var Bundle[]
	 */
	private $activeBundles = [];


	public function __construct()
	{
		$this->symfonyContainerBuilder = new SymfonyContainerBuilder;
		$this->symfonyContainerBuilder->addCompilerPass(new FakeReferencesPass);
	}


	/**
	 * Mirror to compiler passes
	 */
	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);

		$this->loadParameters($config);
		$this->loadBundlesToSymfonyContainerBuilder($config['bundles'], $config['parameters']);
	}


	/**
	 * Mirror to $bundle->compile()
	 */
	public function beforeCompile()
	{
		$this->getContainerBuilderTransformer()->transformFromNetteToSymfony(
			$this->getContainerBuilder(), $this->symfonyContainerBuilder
		);

		$this->addSymfonyContainerAdapter();
		$this->symfonyContainerBuilder->compile();

		$this->getContainerBuilderTransformer()->transformFromSymfonyToNette(
			$this->symfonyContainerBuilder, $this->getContainerBuilder()
		);
	}


	/**
	 * Mirror to $bundle->boot()
	 */
	public function afterCompile(ClassType $class)
	{
		$initializerMethod = $class->getMethod('initialize');
		$initializerMethod->addBody('
			foreach (? as $bundle) {
				$bundle->setContainer($this->getService(?));
				$bundle->boot();
			}', [$this->activeBundles, self::SYMFONY_CONTAINER_SERVICE_NAME]
		);
	}


	private function loadParameters(array $config)
	{
		$this->symfonyContainerBuilder->setParameter('kernel.bundles', $config['bundles']);

		$netteConfig = $this->compiler->getConfig()['parameters'];
		$this->symfonyContainerBuilder->setParameter('kernel.root_dir', $netteConfig['appDir']);
		$this->symfonyContainerBuilder->setParameter('kernel.cache_dir', $netteConfig['tempDir']);
		$this->symfonyContainerBuilder->setParameter('kernel.logs_dir', $netteConfig['tempDir']);
		$this->symfonyContainerBuilder->setParameter('kernel.debug', $netteConfig['debugMode']);
		$this->symfonyContainerBuilder->setParameter('kernel.environment', $netteConfig['environment']);
	}


	/**
	 * @param string[] $bundles
	 * @param array[] $parameters
	 */
	private function loadBundlesToSymfonyContainerBuilder(array $bundles, array $parameters)
	{
		foreach ($bundles as $name => $bundleClass) {
			/** @var Bundle $bundle */
			$bundle = new $bundleClass;
			$this->activeBundles[$name] = $bundle;

			if ($extension = $bundle->getContainerExtension()) {
				$this->symfonyContainerBuilder->registerExtension($extension);
				$extensionParameters = $this->determineParameters($parameters, $name);
				// there it has no influence
				// $this->loadBundleParametersToParameterBag($extensionParameters);
				$this->symfonyContainerBuilder->loadFromExtension($extension->getAlias(), $extensionParameters);
			}
			$bundle->build($this->symfonyContainerBuilder);
		}

	}


	/**
	 * @param array $parameters
	 * @param string $name
	 * @return array
	 */
	private function determineParameters(array $parameters, $name)
	{
		return isset($parameters[$name]) ? $parameters[$name] : [];
	}


	/**
	 * @return ContainerBuilderTransformer
	 */
	private function getContainerBuilderTransformer()
	{
		if ($this->containerBuilderTransformer === NULL) {
			$this->containerBuilderTransformer = new ContainerBuilderTransformer($this->getContainerBuilder());
		}
		return $this->containerBuilderTransformer;
	}


	private function addSymfonyContainerAdapter()
	{
		$this->getContainerBuilder()
			->addDefinition(self::SYMFONY_CONTAINER_SERVICE_NAME)
			->setClass(SymfonyContainerAdapter::class);

	}


	private function loadBundleParametersToParameterBag(array $parameters)
	{
		foreach ($this->activeBundles as $bundle) {
			if ($extension = $bundle->getContainerExtension()) {
				$alias = $extension->getAlias();
				foreach ($parameters as $key => $value) {
					$fqcName = $alias . '.' . $key;
					$finalValue = $value;
					if (is_array($value)) {
						foreach ($value as $subKey => $subValue) {
							$fqcName .= '.' . $subKey;
							$finalValue = $subValue;
						}
					}
					$this->symfonyContainerBuilder->setParameter($fqcName, $finalValue);
				}
			}
		}
	}

}
